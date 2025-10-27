<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Staff extends Model
{
    use HasUuids, HasFactory, SoftDeletes;

    protected $table = 'staff';

    protected $fillable = [
        'user_id',
        'phone',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'photo',
        'employee_id',
        'position',
        'department',
        'hire_date',
        'salary',
        'role',
        'shift_start',
        'shift_end',
        'weekly_hours',
        'working_days',
        'annual_leave_balance',
        'sick_leave_balance',
        'casual_leave_balance',
        'overtime_enabled',
        'overtime_rate',
        'total_overtime_minutes',
        'is_active',
        'tenant_id',
    ];

    protected $casts = [
        'salary' => 'decimal:2',
        'working_days' => 'array',
        'overtime_enabled' => 'boolean',
        'overtime_rate' => 'decimal:2',
        'is_active' => 'boolean',
        'weekly_hours' => 'integer',
        'annual_leave_balance' => 'integer',
        'sick_leave_balance' => 'integer',
        'casual_leave_balance' => 'integer',
        'total_overtime_minutes' => 'integer',
    ];

    protected $appends = [
        'total_leave_balance',
        'name',
        'email',
    ];

    /**
     * Boot the model and generate employee ID.
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($staff) {
            if (empty($staff->employee_id)) {
                $staff->employee_id = static::generateEmployeeId();
            }
        });
    }

    /**
     * Generate a unique employee ID.
     */
    protected static function generateEmployeeId(): string
    {
        do {
            // Generate ID in format: EMP + 4 random digits
            $employeeId = 'EMP' . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT);
        } while (static::where('employee_id', $employeeId)->exists());
        
        return $employeeId;
    }

    /**
     * Get the user account associated with the staff member.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the tenant that owns the staff member.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get all attendance records for this staff member.
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(StaffAttendance::class);
    }

    /**
     * Calculate total overtime pay based on accumulated overtime minutes.
     */
    public function calculateOvertimePay(): float
    {
        if (!$this->overtime_enabled || $this->total_overtime_minutes <= 0) {
            return 0;
        }

        // Calculate hourly rate from salary (assuming monthly salary)
        $hourlyRate = $this->salary / (4 * $this->weekly_hours);
        $overtimeHours = $this->total_overtime_minutes / 60;
        
        return round($overtimeHours * $hourlyRate * $this->overtime_rate, 2);
    }

    /**
     * Get total leave balance (all types combined).
     */
    public function getTotalLeaveBalanceAttribute(): int
    {
        return $this->annual_leave_balance + $this->sick_leave_balance + $this->casual_leave_balance;
    }

    /**
     * Get staff name from user relationship.
     */
    public function getNameAttribute(): ?string
    {
        return $this->user?->name;
    }

    /**
     * Get staff email from user relationship.
     */
    public function getEmailAttribute(): ?string
    {
        return $this->user?->email;
    }

    /**
     * Get shift start time in H:i format.
     */
    public function getShiftStartAttribute($value): ?string
    {
        if (!$value) return $value;
        
        // If it's already in H:i format, return as is
        if (preg_match('/^\d{2}:\d{2}$/', $value)) {
            return $value;
        }
        
        // If it includes seconds, remove them
        if (preg_match('/^\d{2}:\d{2}:\d{2}$/', $value)) {
            return substr($value, 0, 5);
        }
        
        return $value;
    }

    /**
     * Get shift end time in H:i format.
     */
    public function getShiftEndAttribute($value): ?string
    {
        if (!$value) return $value;
        
        // If it's already in H:i format, return as is
        if (preg_match('/^\d{2}:\d{2}$/', $value)) {
            return $value;
        }
        
        // If it includes seconds, remove them
        if (preg_match('/^\d{2}:\d{2}:\d{2}$/', $value)) {
            return substr($value, 0, 5);
        }
        
        return $value;
    }

    /**
     * Get hire date in Y-m-d format for frontend compatibility.
     */
    public function getHireDateAttribute($value): ?string
    {
        if (!$value) return null;
        
        // If it's already in Y-m-d format, return as is
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
            return $value;
        }
        
        // Try to parse and format the date
        try {
            $date = \Carbon\Carbon::parse($value);
            return $date->format('Y-m-d');
        } catch (\Exception $e) {
            return $value;
        }
    }
}
