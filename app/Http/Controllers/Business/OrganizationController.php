<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Notifications\OrganizationWelcome;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Enums\MalaysiaState;

class OrganizationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $organizations = Organization::latest()
                ->paginate(10);
            
            return Inertia::render('business/organizations/Index', [
                'organizations' => $organizations
            ]);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to fetch organizations: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $states = array_column(\App\Enums\MalaysiaState::cases(), 'value');
            return Inertia::render('business/organizations/Create', [
                'states' => $states
            ]);
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
                'email' => 'required|email|unique:organizations,email',
                'phone' => 'required|string|max:20',
                'address' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'state' => ['required', 'string', 'max:255', 'in:' . implode(',', array_column(MalaysiaState::cases(), 'value'))],
                'postal_code' => 'required|string|max:20',
                'country' => 'required|string|max:255',
                'notes' => 'nullable|string',
                'is_active' => 'boolean',
            ]);

            DB::beginTransaction();

            $organization = Organization::create([
                ...$validated,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);

            // Create user account
            $password = Str::random(12);
            $user = \App\Models\User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($password),
                'is_active' => true,
            ])->assignRole('super-admin');

            // Send welcome notification with credentials
            $organization->notify(new OrganizationWelcome($password));

            DB::commit();

            return redirect()->route('business.organizations.index', $organization)
                ->with('success', 'Organization created successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create organization: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Organization $organization)
    {
        try {
            $organization->load(['creator', 'updater']);
            
            return Inertia::render('business/organizations/Show', [
                'organization' => $organization
            ]);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to fetch organization details: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Organization $organization)
    {
        try {
            return Inertia::render('business/organizations/Edit', [
                'organization' => $organization
            ]);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to load edit form: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Organization $organization)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'ssm_number' => 'required|string|max:255',
                'email' => 'required|email|unique:organizations,email,' . $organization->id,
                'phone' => 'required|string|max:20',
                'address' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'state' => ['required', 'string', 'max:255', 'in:' . implode(',', array_column(MalaysiaState::cases(), 'value'))],
                'postal_code' => 'required|string|max:20',
                'country' => 'required|string|max:255',
                'notes' => 'nullable|string',
                'is_active' => 'boolean',
            ]);

            DB::beginTransaction();

            $organization->update([
                ...$validated,
                'updated_by' => Auth::id(),
            ]);

            DB::commit();

            return redirect()->route('business.organizations.index')
                ->with('success', 'Organization updated successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update organization: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Organization $organization)
    {
        try {
            DB::beginTransaction();

            // Find and delete the associated user account
            $user = \App\Models\User::where('email', $organization->email)->first();
            if ($user) {
                $user->delete();
            }

            $organization->delete();

            DB::commit();

            return redirect()->route('business.organizations.index')
                ->with('success', 'Organization deleted successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to delete organization: ' . $e->getMessage());
        }
    }
}
