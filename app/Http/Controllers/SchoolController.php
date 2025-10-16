<?php

namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Enums\MalaysiaState;

class SchoolController extends Controller
{
    public function index()
    {
        $schools = School::latest()->paginate(10);
        return Inertia::render('organization/schools/Index', [
            'schools' => $schools
        ]);
    }

    public function create()
    {
        $malaysiaStates = array_column(MalaysiaState::cases(), 'value');
        return Inertia::render('organization/schools/Create', [
            'states' => $malaysiaStates
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'city' => 'nullable|string|max:255',
            'state' => ['required', 'string', 'max:255', 'in:' . implode(',', array_column(MalaysiaState::cases(), 'value'))],
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'website' => 'nullable|url|max:255',
            'principal_name' => 'required|string|max:255',
            'established_year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'description' => 'nullable|string',
            'status' => 'boolean'
        ]);

        School::create($validated);

        return redirect()->route('schools.index')
            ->with('success', 'School created successfully.');
    }

    public function show(School $school)
    {
        $school->load(['classes', 'students']);
        return Inertia::render('organization/schools/Show', [
            'school' => $school
        ]);
    }

    public function edit(School $school)
    {
        $malaysiaStates = array_column(MalaysiaState::cases(), 'value');
        return Inertia::render('organization/schools/Edit', [
            'school' => $school,
            'states' => $malaysiaStates
        ]);
    }

    public function update(Request $request, School $school)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'city' => 'nullable|string|max:255',
            'state' => ['required', 'string', 'max:255', 'in:' . implode(',', array_column(MalaysiaState::cases(), 'value'))],
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'website' => 'nullable|url|max:255',
            'principal_name' => 'required|string|max:255',
            'established_year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'description' => 'nullable|string',
            'status' => 'boolean'
        ]);

        $school->update($validated);

        return redirect()->route('schools.index')
            ->with('success', 'School updated successfully.');
    }

    public function destroy(School $school)
    {
        $school->delete();

        return redirect()->route('schools.index')
            ->with('success', 'School deleted successfully.');
    }
} 