<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\School;
use App\Models\SchoolClass;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Inertia\Response;

class StudentController extends Controller
{
    public function index(): Response
    {
        $students = Student::with(['school', 'schoolClass'])
            ->latest()
            ->paginate(10);

        return Inertia::render('Students/Index', [
            'students' => $students
        ]);
    }

    public function create(): Response
    {
        $schools = School::where('status', true)->get();
        $classes = SchoolClass::where('status', true)->get();
        return Inertia::render('Students/Create', [
            'schools' => $schools,
            'classes' => $classes
        ]);
    }

    public function store(StoreStudentRequest $request)
    {
        try {
            Student::create($request->validated());

            return redirect()->route('students.index')
                ->with('success', 'Student created successfully.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Failed to create student: ' . $e->getMessage());
        }
    }

    public function show(Student $student): Response
    {
        return Inertia::render('Students/Show', [
            'student' => $student->load('school')
        ]);
    }

    public function edit(Student $student): Response
    {
        $schools = School::where('status', true)->get();
        return Inertia::render('Students/Edit', [
            'student' => $student->load('school'),
            'schools' => $schools
        ]);
    }

    public function update(UpdateStudentRequest $request, Student $student)
    {
        try {
            $student->update($request->validated());

            return redirect()->route('students.index')
                ->with('success', 'Student updated successfully.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Failed to update student: ' . $e->getMessage());
        }
    }

    public function destroy(Student $student)
    {
        try {
            $student->delete();

            return redirect()->route('students.index')
                ->with('success', 'Student deleted successfully.');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Failed to delete student: ' . $e->getMessage());
        }
    }
} 