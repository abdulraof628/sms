<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class StaffAttendance extends Model
{
    use HasUuids, HasFactory;

    protected $table = 'staff_attendance';

    protected $fillable = [
        'staff_id',
        'date',
        'clock_in',
        'clock_out',
        'expected_clock_out',
        'is_late',
        'late_minutes',
        'overtime_minutes',
        'overtime_pay',
        'status',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'clock_in' => 'datetime',
        'clock_out' => 'datetime',
        'expected_clock_out' => 'datetime',
        'is_late' => 'boolean',
        'late_minutes' => 'integer',
        'overtime_minutes' => 'integer',
        'overtime_pay' => 'decimal:2',
    ];

    /**
     * Get the staff member that owns this attendance record.
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    /**
     * Calculate if staff member clocked out late and update overtime.
     */
    public function calculateOvertimeAndLateness(): void
    {
        if (!$this->clock_out || !$this->staff) {
            return;
        }

        $staff = $this->staff;
        $clockOut = Carbon::parse($this->clock_out);
        $expectedClockOut = $this->expected_clock_out 
            ? Carbon::parse($this->expected_clock_out)
            : Carbon::parse($this->date->format('Y-m-d') . ' ' . $staff->shift_end);

        // Calculate late clock-out (overtime)
        if ($clockOut->greaterThan($expectedClockOut)) {
            $overtimeMinutes = $clockOut->diffInMinutes($expectedClockOut);
            $this->overtime_minutes = $overtimeMinutes;

            // Calculate overtime pay if enabled
            if ($staff->overtime_enabled) {
                $hourlyRate = $staff->salary / (4 * $staff->weekly_hours);
                $overtimeHours = $overtimeMinutes / 60;
                $this->overtime_pay = round($overtimeHours * $hourlyRate * $staff->overtime_rate, 2);
            }
        }

        // Check if late to work
        if ($this->clock_in && $staff->shift_start) {
            $clockIn = Carbon::parse($this->clock_in);
            $expectedClockIn = Carbon::parse($this->date->format('Y-m-d') . ' ' . $staff->shift_start);
            
            if ($clockIn->greaterThan($expectedClockIn)) {
                $this->is_late = true;
                $this->late_minutes = $clockIn->diffInMinutes($expectedClockIn);
            }
        }

        $this->save();

        // Update staff's total overtime
        if ($this->overtime_minutes > 0 && $staff->overtime_enabled) {
            $staff->increment('total_overtime_minutes', $this->overtime_minutes);
        }
    }
}
