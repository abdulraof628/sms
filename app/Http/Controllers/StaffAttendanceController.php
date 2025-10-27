<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\StaffAttendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Carbon\Carbon;

class StaffAttendanceController extends Controller
{
    /**
     * Display a listing of attendance records.
     */
    public function index(Request $request): Response
    {
        $currentUser = Auth::user();
        
        if (!$currentUser->tenant_id) {
            $attendance = StaffAttendance::whereRaw('1 = 0')->paginate(10);
            $staff = collect();
        } else {
            $query = StaffAttendance::with(['staff.user'])
                ->whereHas('staff', function ($q) use ($currentUser) {
                    $q->where('tenant_id', $currentUser->tenant_id);
                });

            // Filter by staff member
            if ($request->has('staff_id') && $request->string('staff_id')->isNotEmpty()) {
                $query->where('staff_id', $request->string('staff_id'));
            }

            // Filter by date range
            if ($request->has('date_from') && $request->string('date_from')->isNotEmpty()) {
                $query->whereDate('date', '>=', $request->string('date_from'));
            }
            if ($request->has('date_to') && $request->string('date_to')->isNotEmpty()) {
                $query->whereDate('date', '<=', $request->string('date_to'));
            }

            // Filter by status
            if ($request->has('status') && $request->string('status')->isNotEmpty()) {
                $query->where('status', $request->string('status'));
            }

            $attendance = $query->latest('date')
                ->paginate(10)
                ->withQueryString();

            // Get staff for filter dropdown
            $staff = Staff::with('user:id,name')
                ->where('tenant_id', $currentUser->tenant_id)
                ->where('is_active', true)
                ->get(['id', 'user_id', 'employee_id']);
        }

        return Inertia::render('staff/attendance/Index', [
            'attendance' => $attendance,
            'staff' => $staff,
            'filters' => [
                'staff_id' => (string) $request->string('staff_id'),
                'date_from' => (string) $request->string('date_from'),
                'date_to' => (string) $request->string('date_to'),
                'status' => (string) $request->string('status'),
            ],
        ]);
    }

    /**
     * Clock in a staff member.
     */
    public function clockIn(Request $request, Staff $staff)
    {
        $currentUser = Auth::user();
        
        if ($currentUser->tenant_id && $staff->tenant_id !== $currentUser->tenant_id) {
            abort(403, 'Unauthorized access.');
        }

        $today = Carbon::today();
        
        // Check if already clocked in today
        $existingAttendance = StaffAttendance::where('staff_id', $staff->id)
            ->whereDate('date', $today)
            ->first();

        if ($existingAttendance && $existingAttendance->clock_in) {
            return back()->with('error', 'Staff member has already clocked in today.');
        }

        $clockIn = Carbon::now();
        $expectedClockIn = Carbon::parse($today->format('Y-m-d') . ' ' . $staff->shift_start);
        
        $isLate = $clockIn->greaterThan($expectedClockIn);
        $lateMinutes = $isLate ? $clockIn->diffInMinutes($expectedClockIn) : 0;

        $attendance = StaffAttendance::updateOrCreate(
            [
                'staff_id' => $staff->id,
                'date' => $today,
            ],
            [
                'clock_in' => $clockIn,
                'is_late' => $isLate,
                'late_minutes' => $lateMinutes,
                'status' => 'present',
            ]
        );

        return back()->with('success', 'Clocked in successfully.');
    }

    /**
     * Clock out a staff member.
     */
    public function clockOut(Request $request, Staff $staff)
    {
        $currentUser = Auth::user();
        
        if ($currentUser->tenant_id && $staff->tenant_id !== $currentUser->tenant_id) {
            abort(403, 'Unauthorized access.');
        }

        $today = Carbon::today();
        
        $attendance = StaffAttendance::where('staff_id', $staff->id)
            ->whereDate('date', $today)
            ->first();

        if (!$attendance || !$attendance->clock_in) {
            return back()->with('error', 'Staff member has not clocked in yet.');
        }

        if ($attendance->clock_out) {
            return back()->with('error', 'Staff member has already clocked out.');
        }

        $attendance->clock_out = Carbon::now();
        $attendance->expected_clock_out = Carbon::parse($today->format('Y-m-d') . ' ' . $staff->shift_end);
        $attendance->save();

        // Calculate overtime and lateness
        $attendance->calculateOvertimeAndLateness();

        return back()->with('success', 'Clocked out successfully.');
    }

    /**
     * Store a manually created attendance record.
     */
    public function store(Request $request)
    {
        $currentUser = Auth::user();
        
        $validated = $request->validate([
            'staff_id' => 'required|exists:staff,id',
            'date' => 'required|date',
            'clock_in' => 'nullable|date_format:H:i',
            'clock_out' => 'nullable|date_format:H:i',
            'status' => 'required|in:present,absent,half-day,leave',
            'notes' => 'nullable|string|max:500',
        ]);

        // Check staff belongs to tenant
        $staff = Staff::findOrFail($validated['staff_id']);
        if ($currentUser->tenant_id && $staff->tenant_id !== $currentUser->tenant_id) {
            abort(403, 'Unauthorized access.');
        }

        // Create attendance record
        $attendance = StaffAttendance::create($validated);

        // If both clock in and out are provided, calculate overtime
        if ($validated['clock_in'] && $validated['clock_out']) {
            $attendance->expected_clock_out = Carbon::parse($validated['date'] . ' ' . $staff->shift_end);
            $attendance->save();
            $attendance->calculateOvertimeAndLateness();
        }

        return back()->with('success', 'Attendance record created successfully.');
    }

    /**
     * Update an attendance record.
     */
    public function update(Request $request, StaffAttendance $attendance)
    {
        $currentUser = Auth::user();
        
        if ($currentUser->tenant_id && $attendance->staff->tenant_id !== $currentUser->tenant_id) {
            abort(403, 'Unauthorized access.');
        }

        $validated = $request->validate([
            'clock_in' => 'nullable|date_format:H:i',
            'clock_out' => 'nullable|date_format:H:i',
            'status' => 'required|in:present,absent,half-day,leave',
            'notes' => 'nullable|string|max:500',
        ]);

        $attendance->update($validated);

        // Recalculate overtime if times changed
        if (isset($validated['clock_out'])) {
            $attendance->calculateOvertimeAndLateness();
        }

        return back()->with('success', 'Attendance record updated successfully.');
    }

    /**
     * Delete an attendance record.
     */
    public function destroy(StaffAttendance $attendance)
    {
        $currentUser = Auth::user();
        
        if ($currentUser->tenant_id && $attendance->staff->tenant_id !== $currentUser->tenant_id) {
            abort(403, 'Unauthorized access.');
        }

        // Deduct overtime minutes from staff total if applicable
        if ($attendance->overtime_minutes > 0 && $attendance->staff->overtime_enabled) {
            $attendance->staff->decrement('total_overtime_minutes', $attendance->overtime_minutes);
        }

        $attendance->delete();

        return back()->with('success', 'Attendance record deleted successfully.');
    }
}
