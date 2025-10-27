<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Enums\Status;

class BranchController extends Controller
{
    public function index(Request $request)
    {
        $branches = Branch::with('principal')
            ->when($request->string('search')->isNotEmpty(), function ($query) use ($request) {
                $term = $request->string('search');
                $query->where(function ($q) use ($term) {
                    $q->where('name', 'like', "%{$term}%")
                      ->orWhere('address', 'like', "%{$term}%")
                      ->orWhere('city', 'like', "%{$term}%")
                      ->orWhere('state', 'like', "%{$term}%")
                      ->orWhereHas('principal', function ($userQuery) use ($term) {
                          $userQuery->where('name', 'like', "%{$term}%");
                      });
                });
            })
            ->when($request->string('status')->isNotEmpty(), function ($query) use ($request) {
                $status = (int)(string) $request->string('status');
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('organization/branches/Index', [
            'branches' => $branches,
            'filters' => [
                'search' => (string) $request->string('search'),
                'status' => (string) $request->string('status'),
            ],
            'statusOptions' => array_merge(
                [['value' => '', 'label' => 'All Status']],
                collect(Status::cases())->map(fn($status) => ['value' => (string)$status->value, 'label' => $status->getLabel()])->toArray()
            ),
        ]);
    }

    public function create()
    {
        $principals = User::role('principal')->where('is_active', true)->get(['id', 'name', 'email']);
        return Inertia::render('organization/branches/Create', [
            'principals' => $principals
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'city' => 'nullable|string|max:255',
            'state' => 'required|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'website' => 'nullable|url|max:255',
            'principal_id' => 'required|uuid|exists:users,id',
            'established_year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'description' => 'nullable|string',
            'status' => 'boolean'
        ]);

        Branch::create($validated);

        return redirect()->route('branches.index')
            ->with('success', 'Branch created successfully.');
    }

    public function show(Branch $branch)
    {
        $branch->load(['classes', 'students', 'principal']);
        return Inertia::render('organization/branches/Show', [
            'branch' => $branch
        ]);
    }

    public function edit(Branch $branch)
    {
        $principals = User::role('principal')->where('is_active', true)->get(['id', 'name', 'email']);
        return Inertia::render('organization/branches/Edit', [
            'branch' => $branch,
            'principals' => $principals
        ]);
    }

    public function update(Request $request, Branch $branch)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'city' => 'nullable|string|max:255',
            'state' => 'required|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'website' => 'nullable|url|max:255',
            'principal_id' => 'required|uuid|exists:users,id',
            'established_year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'description' => 'nullable|string',
            'status' => 'boolean'
        ]);

        $branch->update($validated);

        return redirect()->route('branches.index')
            ->with('success', 'Branch updated successfully.');
    }

    public function destroy(Branch $branch)
    {
        $branch->delete();

        return redirect()->route('branches.index')
            ->with('success', 'Branch deleted successfully.');
    }
}

