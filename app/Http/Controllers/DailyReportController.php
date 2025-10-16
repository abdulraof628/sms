<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDailyReportRequest;
use App\Http\Requests\UpdateDailyReportRequest;
use App\Models\DailyReport;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;

class DailyReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        
        if ($user->hasRole('teacher')) {
            // Teachers see reports they've created
            $reports = DailyReport::with(['student', 'teacher'])
                ->where('teacher_id', $user->id)
                ->latest('date')
                ->paginate(10);
        } elseif ($user->hasRole('parent')) {
            // Parents see reports for their children
            $childrenIds = $user->children->pluck('id');
            $reports = DailyReport::with(['student', 'teacher'])
                ->whereIn('student_id', $childrenIds)
                ->where('status', 'published')
                ->latest('date')
                ->paginate(10);
        } else {
            // Admins see all reports
            $reports = DailyReport::with(['student', 'teacher'])
                ->latest('date')
                ->paginate(10);
        }

        return Inertia::render('teachers/daily/Index', [
            'reports' => $reports
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create', DailyReport::class);
        
        $students = User::role('student')
            ->select('id', 'name')
            ->orderBy('name')
            ->get();
            
        return Inertia::render('teachers/daily/Create', [
            'students' => $students
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDailyReportRequest $request): \Illuminate\Http\RedirectResponse
    {
        Gate::authorize('create', DailyReport::class);
        
        $validated = $request->validated();
        $dailyReport = DailyReport::create([
            'student_id' => $validated['student_id'],
            'teacher_id' => Auth::id(),
            'date' => $validated['date'],
            'report' => $validated['report'],
            'status' => $validated['status'] ?? 'draft',
        ]);
        
        return redirect()
            ->route('daily.index')
            ->with('success', 'Daily report created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(DailyReport $dailyReport)
    {
        Gate::authorize('view', $dailyReport);
        
        $dailyReport->load(['student', 'teacher']);
        
        return Inertia::render('teachers/daily/Show', [
            'report' => $dailyReport
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DailyReport $dailyReport)
    {
        Gate::authorize('update', $dailyReport);
        
        $students = User::role('student')
            ->select('id', 'name')
            ->orderBy('name')
            ->get();
            
        return Inertia::render('teachers/daily/Edit', [
            'daily' => $dailyReport->load('student'),
            'students' => $students
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDailyReportRequest $request, DailyReport $dailyReport): \Illuminate\Http\RedirectResponse
    {
        Gate::authorize('update', $dailyReport);
        
        $validated = $request->validated();
        $dailyReport->update([
            'student_id' => $validated['student_id'],
            'date' => $validated['date'],
            'report' => $validated['report'],
            'status' => $validated['status'],
        ]);
        
        return redirect()
            ->route('daily.index')
            ->with('success', 'Daily report updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DailyReport $dailyReport): \Illuminate\Http\RedirectResponse
    {
        Gate::authorize('delete', $dailyReport);
        
        $dailyReport->delete();
        
        return redirect()
            ->route('daily.index')
            ->with('success', 'Daily report deleted successfully.');
    }
}
