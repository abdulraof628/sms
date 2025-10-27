<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\User;
use App\Enums\Status;
use App\Enums\StaffRole;
use App\Http\Requests\StoreStaffRequest;
use App\Http\Requests\UpdateStaffRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Notifications\StaffWelcome;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $currentUser = Auth::user();
        
        // Filter staff by current user's tenant
        if (!$currentUser->tenant_id) {
            $staff = Staff::whereRaw('1 = 0')->paginate(10); // Empty result set
        } else {
            $staff = Staff::with(['tenant', 'user'])
                ->where('tenant_id', $currentUser->tenant_id)
                ->when($request->filled('search'), function ($query) use ($request) {
                    $term = $request->string('search');
                    $query->where(function ($q) use ($term) {
                        $q->where('employee_id', 'like', "%{$term}%")
                          ->orWhere('position', 'like', "%{$term}%")
                          ->orWhereHas('user', function ($userQuery) use ($term) {
                              $userQuery->where('name', 'like', "%{$term}%")
                                       ->orWhere('email', 'like', "%{$term}%");
                          });
                    });
                })
                ->when($request->filled('role'), function ($query) use ($request) {
                    $query->where('role', $request->string('role'));
                })
                ->when($request->filled('status'), function ($query) use ($request) {
                    $status = (int)(string) $request->string('status');
                    $query->where('is_active', $status);
                })
                ->latest()
                ->paginate(10)
                ->withQueryString();
        }

        return Inertia::render('staff/Index', [
            'staff' => $staff,
            'filters' => [
                'search' => (string) $request->string('search'),
                'role' => (string) $request->string('role'),
                'status' => (string) $request->string('status'),
            ],
            'roleOptions' => array_merge(
                [['value' => '', 'label' => 'All Roles']],
                collect(StaffRole::cases())->map(fn($role) => ['value' => $role->value, 'label' => $role->getLabel()])->toArray()
            ),
            'statusOptions' => array_merge(
                [['value' => '', 'label' => 'All Status']],
                collect(Status::cases())->map(fn($status) => ['value' => (string)$status->value, 'label' => $status->getLabel()])->toArray()
            ),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('staff/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $currentUser = Auth::user();
        
        $validated = $request->validate([
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'photo' => 'nullable|image|max:2048',
            'employee_id' => 'nullable|string|max:50|unique:staff', // Made optional - auto-generated
            'position' => 'nullable|string|max:100',
            'department' => 'nullable|string|max:100',
            'hire_date' => 'nullable|date',
            'salary' => 'nullable|numeric|min:0',
            'role' => 'required|in:admin,principal,teacher',
            'shift_start' => 'nullable|date_format:H:i',
            'shift_end' => 'nullable|date_format:H:i',
            'weekly_hours' => 'nullable|integer|min:1|max:168',
            'working_days' => 'nullable|array',
            'annual_leave_balance' => 'nullable|integer|min:0',
            'sick_leave_balance' => 'nullable|integer|min:0',
            'casual_leave_balance' => 'nullable|integer|min:0',
            'overtime_enabled' => 'nullable|boolean',
            'overtime_rate' => 'nullable|numeric|min:1|max:5',
            'is_active' => 'nullable|boolean',
        ]);

        // Handle photo upload with compression
        if ($request->hasFile('photo')) {
            try {
                $photo = $request->file('photo');
                $filename = 'staff-' . uniqid() . '.jpg';
                
                // Create image manager with GD driver
                $manager = new ImageManager(new Driver());
                
                // Compress and resize image
                $image = $manager->read($photo->getPathname());
                $image->cover(400, 400); // Resize to 400x400 (square)
                $encoded = $image->toJpeg(80); // Compress to 80% quality
                
                // Save to storage
                Storage::disk('public')->put('staff-photos/' . $filename, (string) $encoded);
                $validated['photo'] = 'staff-photos/' . $filename;
            } catch (\Exception $e) {
                Log::error('Failed to process photo: ' . $e->getMessage());
                return back()->withErrors(['photo' => 'Failed to process photo. Please try another image.']);
            }
        }

        // Set tenant_id to current user's tenant
        $validated['tenant_id'] = $currentUser->tenant_id;

        // Validate user fields separately
        $userValidated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
        ]);

        // Create user account for the staff member
        $password = Str::random(12); // Generate random password
        $user = User::create([
            'name' => $userValidated['name'],
            'email' => $userValidated['email'],
            'password' => Hash::make($password),
            'tenant_id' => $currentUser->tenant_id,
            'is_active' => $validated['is_active'] ?? true, // Match staff active status
        ]);

        // Assign role based on staff role
        if ($validated['role'] === 'admin') {
            $user->assignRole('admin');
        } elseif ($validated['role'] === 'principal') {
            $user->assignRole('principal');
        } else {
            $user->assignRole('teacher');
        }

        // Link user to staff
        $validated['user_id'] = $user->id;

        $staff = Staff::create($validated);

        // Send welcome notification to the staff member with login credentials
        try {
            Notification::route('mail', $staff->email)
                ->notify(new StaffWelcome(
                    employeeId: $staff->employee_id,
                    staffName: $staff->name,
                    position: $staff->position,
                    department: $staff->department,
                    password: $password,
                    email: $staff->email
                ));
        } catch (\Exception $e) {
            // Log the error but don't fail the staff creation
            Log::error('Failed to send staff welcome email: ' . $e->getMessage());
        }

        return redirect()->route('staff.index')
            ->with('success', 'Staff member and user account created successfully. Welcome email sent with login credentials.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Staff $staff): Response
    {
        $currentUser = Auth::user();
        
        // Check if staff belongs to the same tenant
        if ($currentUser->tenant_id && $staff->tenant_id !== $currentUser->tenant_id) {
            abort(403, 'Unauthorized access to staff member.');
        }
        
        $staff->load(['tenant', 'user', 'attendances' => function ($query) {
            $query->latest('date')->limit(10);
        }]);

        // Calculate overtime pay
        $overtimePay = $staff->calculateOvertimePay();

        return Inertia::render('staff/Show', [
            'staff' => $staff,
            'overtimePay' => $overtimePay,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Staff $staff): Response
    {
        $currentUser = Auth::user();
        
        // Check if staff belongs to the same tenant
        if ($currentUser->tenant_id && $staff->tenant_id !== $currentUser->tenant_id) {
            abort(403, 'Unauthorized access to staff member.');
        }
        
        $staff->load('user');
        
        return Inertia::render('staff/Edit', [
            'staff' => $staff,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Staff $staff)
    {
        $currentUser = Auth::user();
        
        // Check if staff belongs to the same tenant
        if ($currentUser->tenant_id && $staff->tenant_id !== $currentUser->tenant_id) {
            abort(403, 'Unauthorized access to staff member.');
        }
        
        // Validate user fields separately
        if ($staff->user) {
            $userValidated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $staff->user_id,
            ]);

            // Update associated user
            $staff->user->update([
                'name' => $userValidated['name'],
                'email' => $userValidated['email'],
            ]);
        }

        $validated = $request->validate([
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'photo' => 'nullable|image|max:2048',
            'employee_id' => 'nullable|string|max:50|unique:staff,employee_id,' . $staff->id, // Made optional
            'position' => 'nullable|string|max:100',
            'department' => 'nullable|string|max:100',
            'hire_date' => 'nullable|date',
            'salary' => 'nullable|numeric|min:0',
            'role' => 'required|in:admin,principal,teacher',
            'shift_start' => 'nullable|date_format:H:i',
            'shift_end' => 'nullable|date_format:H:i',
            'weekly_hours' => 'nullable|integer|min:1|max:168',
            'working_days' => 'nullable|array',
            'annual_leave_balance' => 'nullable|integer|min:0',
            'sick_leave_balance' => 'nullable|integer|min:0',
            'casual_leave_balance' => 'nullable|integer|min:0',
            'overtime_enabled' => 'nullable|boolean',
            'overtime_rate' => 'nullable|numeric|min:1|max:5',
            'is_active' => 'nullable|boolean',
        ]);

        // Handle photo upload with compression
        if ($request->hasFile('photo')) {
            try {
                // Delete old photo if exists
                if ($staff->photo) {
                    Storage::disk('public')->delete($staff->photo);
                }
                
                $photo = $request->file('photo');
                $filename = 'staff-' . uniqid() . '.jpg';
                
                // Create image manager with GD driver
                $manager = new ImageManager(new Driver());
                
                // Compress and resize image
                $image = $manager->read($photo->getPathname());
                $image->cover(400, 400); // Resize to 400x400 (square)
                $encoded = $image->toJpeg(80); // Compress to 80% quality
                
                // Save to storage
                Storage::disk('public')->put('staff-photos/' . $filename, (string) $encoded);
                $validated['photo'] = 'staff-photos/' . $filename;
            } catch (\Exception $e) {
                Log::error('Failed to process photo: ' . $e->getMessage());
                return back()->withErrors(['photo' => 'Failed to process photo. Please try another image.']);
            }
        }

        $staff->update([
            ...$validated,
            'updated_by' => $currentUser->id,
        ]);

        return redirect()->route('staff.show', $staff)
            ->with('success', 'Staff member updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Staff $staff)
    {
        $currentUser = Auth::user();
        
        // Check if staff belongs to the same tenant
        if ($currentUser->tenant_id && $staff->tenant_id !== $currentUser->tenant_id) {
            abort(403, 'Unauthorized access to staff member.');
        }
        
        try {
            DB::beginTransaction();
            
            // Delete photo if exists
            if ($staff->photo) {
                Storage::disk('public')->delete($staff->photo);
            }
            
            // Delete all associated attendance records (if not handled by cascade)
            $staff->attendances()->delete();
            
            // Handle associated user account
            if ($staff->user) {
                // Remove all roles from the user
                $staff->user->syncRoles([]);
                
                // Delete the user account completely
                $staff->user->delete();
            }
            
            // Finally, delete the staff record (soft delete)
            $staff->delete();
            
            DB::commit();
            
            return redirect()->route('staff.index')
                ->with('success', 'Staff member and all associated data deleted successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to delete staff member: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Failed to delete staff member. Please try again.');
        }
    }

    /**
     * Toggle the active status of a staff member.
     */
    public function toggleStatus(Staff $staff)
    {
        $currentUser = Auth::user();
        
        // Check if staff belongs to the same tenant
        if ($currentUser->tenant_id && $staff->tenant_id !== $currentUser->tenant_id) {
            abort(403, 'Unauthorized access to staff member.');
        }
        
        $newStatus = !$staff->is_active;
        
        // Update staff status
        $staff->update(['is_active' => $newStatus]);
        
        // Also update the associated user's status if they have a user account
        if ($staff->user) {
            $staff->user->update(['is_active' => $newStatus]);
        }
        
        $statusText = $newStatus ? 'activated' : 'deactivated';
        $message = "Staff member {$statusText} successfully.";
        
        if ($staff->user) {
            $message .= " Associated user account has also been {$statusText}.";
        }
        
        return back()->with('success', $message);
    }
}
