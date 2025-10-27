<?php

namespace App\Policies;

use App\Models\DailyReport;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DailyReportPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Teachers can view all reports they've created
        // Parents can view reports for their children
        return $user->hasAnyRole(['teacher', 'parent']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, DailyReport $dailyReport): bool
    {
        // Teacher can view their own reports
        if ($user->hasRole('teacher') && $user->id === $dailyReport->teacher_id) {
            return true;
        }
        
        // Parent can view reports for their children
        if ($user->hasRole('parent') && $user->children->contains('id', $dailyReport->student_id)) {
            return true;
        }
        
        // Admin can view all reports
        return $user->hasRole('super-admin');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only teachers can create daily reports
        return $user->hasRole(['teacher', 'super-admin']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, DailyReport $dailyReport): bool
    {
        // Only the teacher who created the report can update it
        return $user->hasRole(['teacher', 'super-admin']) && $user->id === $dailyReport->teacher_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, DailyReport $dailyReport): bool
    {
        // Only the teacher who created the report or admin can delete it
        return ($user->hasRole(['teacher', 'super-admin']) && $user->id === $dailyReport->teacher_id) || 
               $user->hasRole('super-admin');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, DailyReport $dailyReport): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, DailyReport $dailyReport): bool
    {
        return false;
    }
}
