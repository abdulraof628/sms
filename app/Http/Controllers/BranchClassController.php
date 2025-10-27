<?php

namespace App\Http\Controllers;

use App\Models\BranchClass;
use App\Models\Branch;
use App\Models\Staff;
use App\Enums\MalaysiaCurriculumType;
use App\Enums\MalaysiaGradeLevel;
use App\Enums\MalaysiaClassStream;
use App\Enums\MalaysiaLanguageMedium;
use App\Enums\Status;
use App\Http\Requests\StoreBranchClassRequest;
use App\Http\Requests\UpdateBranchClassRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BranchClassController extends Controller
{
    public function index(Request $request): Response
    {
        $classes = BranchClass::with('branch', 'advisor')
            ->when($request->string('search')->isNotEmpty(), function ($query) use ($request) {
                $term = $request->string('search');
                $query->where(function ($q) use ($term) {
                    $q->where('name', 'like', "%{$term}%")
                      ->orWhere('code', 'like', "%{$term}%")
                      ->orWhere('description', 'like', "%{$term}%")
                      ->orWhere('academic_session', 'like', "%{$term}%")
                      ->orWhereHas('branch', function ($branchQuery) use ($term) {
                          $branchQuery->where('name', 'like', "%{$term}%");
                      });
                });
            })
            ->when($request->string('branch_id')->isNotEmpty(), function ($query) use ($request) {
                $query->where('branch_id', $request->string('branch_id'));
            })
            ->when($request->string('curriculum_type')->isNotEmpty(), function ($query) use ($request) {
                $query->where('curriculum_type', $request->string('curriculum_type'));
            })
            ->when($request->string('grade_level')->isNotEmpty(), function ($query) use ($request) {
                $query->where('grade_level', $request->string('grade_level'));
            })
            ->when($request->string('status')->isNotEmpty(), function ($query) use ($request) {
                $status = (int)(string) $request->string('status');
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $branches = Branch::where('status', true)->get();

        return Inertia::render('organization/classes/Index', [
            'classes' => $classes,
            'branches' => $branches,
            'filters' => [
                'search' => (string) $request->string('search'),
                'branch_id' => (string) $request->string('branch_id'),
                'curriculum_type' => (string) $request->string('curriculum_type'),
                'grade_level' => (string) $request->string('grade_level'),
                'status' => (string) $request->string('status'),
            ],
            'statusOptions' => array_merge(
                [['value' => '', 'label' => 'All Status']],
                collect(Status::cases())->map(fn($status) => ['value' => (string)$status->value, 'label' => $status->getLabel()])->toArray()
            ),
        ]);
    }

    public function create(): Response
    {
        $branches = Branch::where('status', true)->get();
        $staff = Staff::where('is_active', true)->get();
        
        return Inertia::render('organization/classes/Create', [
            'branches' => $branches,
            'staff' => $staff,
            'curriculums' => collect(MalaysiaCurriculumType::cases())->map(fn($c) => ['value' => $c->value, 'label' => $c->getLabel()])->values(),
            'gradeLevels' => collect(MalaysiaGradeLevel::cases())->map(fn($g) => ['value' => $g->value, 'label' => $g->getLabel()])->values(),
            'streams' => collect(MalaysiaClassStream::cases())->map(fn($s) => ['value' => $s->value, 'label' => $s->getLabel()])->values(),
            'languageMediums' => collect(MalaysiaLanguageMedium::cases())->map(fn($l) => ['value' => $l->value, 'label' => $l->getLabel()])->values(),
        ]);
    }

    public function store(StoreBranchClassRequest $request)
    {
        BranchClass::create($request->validated());

        return redirect()->route('classes.index')
            ->with('success', 'Class created successfully.');
    }

    public function show(BranchClass $class): Response
    {
        return Inertia::render('organization/classes/Show', [
            'class' => $class->load('branch', 'advisor', 'students')
        ]);
    }

    public function edit(BranchClass $class): Response
    {
        $branches = Branch::where('status', true)->get();
        $staff = Staff::where('is_active', true)->get();
        
        return Inertia::render('organization/classes/Edit', [
            'classData' => $class->load('branch'),
            'branches' => $branches,
            'staff' => $staff,
            'curriculums' => collect(MalaysiaCurriculumType::cases())->map(fn($c) => ['value' => $c->value, 'label' => $c->getLabel()])->values(),
            'gradeLevels' => collect(MalaysiaGradeLevel::cases())->map(fn($g) => ['value' => $g->value, 'label' => $g->getLabel()])->values(),
            'streams' => collect(MalaysiaClassStream::cases())->map(fn($s) => ['value' => $s->value, 'label' => $s->getLabel()])->values(),
            'languageMediums' => collect(MalaysiaLanguageMedium::cases())->map(fn($l) => ['value' => $l->value, 'label' => $l->getLabel()])->values(),
        ]);
    }

    public function update(UpdateBranchClassRequest $request, BranchClass $class)
    {
        $class->update($request->validated());

        return redirect()->route('classes.index')
            ->with('success', 'Class updated successfully.');
    }

    public function destroy(BranchClass $class)
    {
        $class->delete();

        return redirect()->route('classes.index')
            ->with('success', 'Class deleted successfully.');
    }
}

