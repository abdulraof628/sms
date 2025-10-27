<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Notifications\TenantWelcome;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Enums\Status;

class TenantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $tenants = Tenant::query()
                ->when($request->string('search')->isNotEmpty(), function ($query) use ($request) {
                    $term = $request->string('search');
                    $query->where(function ($q) use ($term) {
                        $q->where('name', 'like', "%{$term}%")
                          ->orWhere('email', 'like', "%{$term}%")
                          ->orWhere('phone', 'like', "%{$term}%");
                    });
                })
                ->when($request->string('status')->isNotEmpty(), function ($query) use ($request) {
                    $status = (int)(string) $request->string('status');
                    $query->where('is_active', $status);
                })
                ->latest()
                ->paginate(10)
                ->withQueryString();
            
            return Inertia::render('superadmin/tenants/Index', [
                'tenants' => $tenants,
                'filters' => [
                    'search' => (string) $request->string('search'),
                    'status' => (string) $request->string('status'),
                ],
                'statusOptions' => array_merge(
                    [['value' => '', 'label' => 'All Status']],
                    collect(Status::cases())->map(fn($status) => ['value' => (string)$status->value, 'label' => $status->getLabel()])->toArray()
                ),
            ]);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to fetch tenants: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            return Inertia::render('superadmin/tenants/Create');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to load create form: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'ssm_number' => 'required|string|max:255',
                'email' => 'required|email|unique:tenants,email',
                'phone' => 'required|string|max:20',
                'address' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'state' => 'required|string|max:255',
                'postal_code' => 'required|string|max:20',
                'country' => 'required|string|max:255',
                'notes' => 'nullable|string',
                'is_active' => 'boolean',
            ]);

            DB::beginTransaction();

            $tenant = Tenant::create([
                ...$validated,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);

            // Create user account for the tenant with admin role
            $password = Str::random(12);
            $user = \App\Models\User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($password),
                'tenant_id' => $tenant->id,
                'is_active' => true,
            ])->assignRole('admin');

            DB::commit();

            // Send welcome email notification with credentials (after commit to ensure data is saved)
            try {
                $user->notify(new TenantWelcome($password, $validated['name']));
                $emailStatus = 'A welcome email with login credentials has been sent to ' . $validated['email'];
            } catch (\Exception $emailException) {
                $emailStatus = 'Tenant created, but the welcome email could not be sent. Please contact the administrator.';
            }

            return redirect()->route('superadmin.tenants.index', $tenant)
                ->with('success', 'Tenant created successfully. ' . $emailStatus);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create tenant: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Tenant $tenant)
    {
        try {
            $tenant->load(['creator', 'updater']);
            
            return Inertia::render('superadmin/tenants/Show', [
                'tenant' => $tenant
            ]);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to fetch tenant details: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tenant $tenant)
    {
        try {
            return Inertia::render('superadmin/tenants/Edit', [
                'tenant' => $tenant
            ]);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to load edit form: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tenant $tenant)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'ssm_number' => 'required|string|max:255',
                'email' => 'required|email|unique:tenants,email,' . $tenant->id,
                'phone' => 'required|string|max:20',
                'address' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'state' => 'required|string|max:255',
                'postal_code' => 'required|string|max:20',
                'country' => 'required|string|max:255',
                'notes' => 'nullable|string',
                'is_active' => 'boolean',
            ]);

            DB::beginTransaction();

            $tenant->update([
                ...$validated,
                'updated_by' => Auth::id(),
            ]);

            DB::commit();

            return redirect()->route('superadmin.tenants.index')
                ->with('success', 'Tenant updated successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update tenant: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tenant $tenant)
    {
        try {
            DB::beginTransaction();

            // Find and delete the associated user account
            $user = \App\Models\User::where('email', $tenant->email)->first();
            if ($user) {
                $user->delete();
            }

            $tenant->delete();

            DB::commit();

            return redirect()->route('superadmin.tenants.index')
                ->with('success', 'Tenant deleted successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to delete tenant: ' . $e->getMessage());
        }
    }

    /**
     * Toggle the tenant's active status and deactivate all tenant users if tenant is deactivated.
     */
    public function toggleStatus(Tenant $tenant)
    {
        try {
            DB::beginTransaction();

            // Toggle tenant status
            $tenant->update([
                'is_active' => !$tenant->is_active,
                'updated_by' => Auth::id(),
            ]);

            // If tenant is being deactivated, deactivate all users belonging to this tenant
            // If tenant is being reactivated, reactivate all users belonging to this tenant
            \App\Models\User::where('tenant_id', $tenant->id)
                ->update(['is_active' => $tenant->is_active]);

            DB::commit();

            $status = $tenant->is_active ? 'activated' : 'deactivated';
            $userCount = \App\Models\User::where('tenant_id', $tenant->id)->count();
            $message = "Tenant {$status} successfully.";
            
            if ($tenant->is_active) {
                $message .= " All {$userCount} tenant users have been reactivated.";
            } else {
                $message .= " All {$userCount} tenant users have been deactivated.";
            }

            return back()->with('success', $message);
        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update tenant status: ' . $e->getMessage());
        }
    }
}
