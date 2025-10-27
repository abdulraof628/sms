<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Branch;
use App\Models\BranchClass;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Inertia\Response;

class StudentController extends Controller
{
    public function index(Request $request): Response
    {
        $students = Student::with(['branch', 'branchClass'])
            ->when($request->string('search')->isNotEmpty(), function ($query) use ($request) {
                $term = $request->string('search');
                $query->where(function ($q) use ($term) {
                    $q->where('first_name', 'like', "%{$term}%")
                      ->orWhere('last_name', 'like', "%{$term}%")
                      ->orWhere('email', 'like', "%{$term}%")
                      ->orWhere('phone', 'like', "%{$term}%")
                      ->orWhere('admission_number', 'like', "%{$term}%")
                      ->orWhere('roll_number', 'like', "%{$term}%")
                      ->orWhere('parent_name', 'like', "%{$term}%")
                      ->orWhere('parent_phone', 'like', "%{$term}%")
                      ->orWhere('parent_email', 'like', "%{$term}%")
                      ->orWhere('class', 'like', "%{$term}%")
                      ->orWhere('section', 'like', "%{$term}%")
                      ->orWhereHas('branch', function ($branchQuery) use ($term) {
                          $branchQuery->where('name', 'like', "%{$term}%");
                      });
                });
            })
            ->when($request->filled('branch'), function ($query) use ($request) {
                $query->where('branch_id', $request->string('branch'));
            })
            ->when($request->filled('class'), function ($query) use ($request) {
                $query->where('class', $request->string('class'));
            })
            ->when($request->filled('gender'), function ($query) use ($request) {
                $query->where('gender', $request->string('gender'));
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->boolean('status'));
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        // Get filter options
        $branches = Branch::where('status', true)->get(['id', 'name']);
        $classes = Student::distinct()->pluck('class')->filter()->sort()->values();
        $genders = ['male', 'female', 'other'];

        return Inertia::render('organization/students/Index', [
            'students' => $students,
            'branches' => $branches,
            'classes' => $classes,
            'genders' => $genders,
            'filters' => [
                'search' => (string) $request->string('search'),
                'branch' => (string) $request->string('branch'),
                'class' => (string) $request->string('class'),
                'gender' => (string) $request->string('gender'),
                'status' => $request->has('status') ? $request->boolean('status') : null,
            ],
        ]);
    }

    public function create(): Response
    {
        $branches = Branch::where('status', true)->get();
        $classes = BranchClass::where('status', true)->get();
        return Inertia::render('organization/students/Create', [
            'branches' => $branches,
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
        return Inertia::render('organization/students/Show', [
            'student' => $student->load('branch')
        ]);
    }

    public function edit(Student $student): Response
    {
        $branches = Branch::where('status', true)->get();
        return Inertia::render('organization/students/Edit', [
            'student' => $student->load('branch'),
            'branches' => $branches
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