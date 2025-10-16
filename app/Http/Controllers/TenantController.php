<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class TenantController extends Controller
{
    public function index()
    {
        $tenants = Tenant::with('domains')->get()->map(function ($tenant) {
            return [
                'id' => $tenant->id,
                'domain' => $tenant->domains->first()?->domain,
                'name' => $tenant->name,
                'email' => $tenant->email,
                'created_at' => $tenant->created_at,
            ];
        });

        return Inertia::render('Tenants/Index', [
            'tenants' => $tenants,
        ]);
    }

    public function create()
    {
        return Inertia::render('Tenants/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'domain' => 'required|string|max:255|unique:domains,domain',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ]);
        
        $tenant = Tenant::create([ 
            'id' => Str::uuid(),
            'name' => $request->name,
            'email' => $request->email,
        ]);

        $tenant->domains()->create(['domain' => $request->domain]);

        // Create admin user for the tenant
        if ($request->email) {
            $password = Str::random(12);
            $user = User::create([  
                'name' => $request->name ?? explode('@', $request->email)[0],
                'email' => $request->email,
                'password' => Hash::make($password),
            ])->assignRole('super-admin');

            // Send credentials email
            Mail::send('emails.tenant-credentials', [
                'name' => $request->name ?? explode('@', $request->email)[0],
                'email' => $request->email,
                'password' => $password,
                'domain' => $request->domain
            ], function ($message) use ($request) {
                $message->to($request->email)
                    ->subject('Your Account Credentials');
            });
        }

        return redirect()->route('tenants.index')
            ->with('success', 'Tenant created successfully.');
    }

    public function destroy(Tenant $tenant)
    {
        try {
            if (!$tenant) {
                return redirect()->route('tenants.index')
                    ->with('error', 'Tenant not found.');
            }
            
            $tenant->delete();
            
            return redirect(route('tenants.index'))
                ->with('success', 'Tenant deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            return redirect()->route('tenants.index')
                ->with('error', 'Error deleting tenant: ' . $e->getMessage());
        }
    }

    public function edit(Tenant $tenant)
    {
        return Inertia::render('Tenants/Edit', [
            'tenant' => [
                'id' => $tenant->id,
                'name' => $tenant->name,
                'email' => $tenant->email,
                'domain' => $tenant->domains->first()?->domain,
            ]
        ]);
    }

    public function update(Request $request, Tenant $tenant)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'domain' => 'nullable|string|max:255|unique:domains,domain,' . $tenant->domains->first()?->id,
        ]);

        $tenant->update([
            'name' => $request->name,   
            'email' => $request->email,
            'domain' => $request->domain,
        ]);

        if ($request->domain) {
            $tenant->domains()->updateOrCreate(
                ['tenant_id' => $tenant->id],
                ['domain' => $request->domain]
            );
        }

        return redirect()->route('tenants.index')
            ->with('success', 'Tenant updated successfully.');
    }
} 