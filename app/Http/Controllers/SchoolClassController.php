<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Inertia\Response;

class SchoolClassController extends Controller
{
    public function index(): Response
    {
        $classes = SchoolClass::with('school')
            ->latest()
            ->paginate(10);

        return Inertia::render('organization/classes/Index', [
            'classes' => $classes
        ]);
    }

    public function create(): Response
    {
        $schools = School::where('status', true)->get();
        return Inertia::render('organization/classes/Create', [
            'schools' => $schools
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'school_id' => 'required|exists:schools,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:school_classes',
            'description' => 'nullable|string',
            'status' => 'boolean',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        SchoolClass::create($validator->validated());

        return redirect()->route('classes.index')
            ->with('success', 'Class created successfully.');
    }

    public function show(SchoolClass $class): Response
    {
        return Inertia::render('organization/classes/Show', [
            'class' => $class->load('school')
        ]);
    }

    public function edit(SchoolClass $class): Response
    {
        $schools = School::where('status', true)->get();
        return Inertia::render('organization/classes/Edit', [
            'classData' => $class->load('school'),
            'schools' => $schools
        ]);
    }

    public function update(Request $request, SchoolClass $class)
    {
        $validator = Validator::make($request->all(), [
            'school_id' => 'required|exists:schools,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:school_classes,code,' . $class->id,
            'description' => 'nullable|string',
            'status' => 'boolean',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $class->update($validator->validated());

        return redirect()->route('classes.index')
            ->with('success', 'Class updated successfully.');
    }

    public function destroy(SchoolClass $class)
    {
        $class->delete();

        return redirect()->route('classes.index')
            ->with('success', 'Class deleted successfully.');
    }
} 