<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Enums\MalaysiaCurriculumType;
use App\Enums\MalaysiaGradeLevel;
use App\Enums\MalaysiaClassStream;
use App\Enums\MalaysiaLanguageMedium;

class BranchClass extends Model
{
    use HasFactory, HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'branch_id',
        'name',
        'code',
        'curriculum_type',
        'grade_level',
        'stream',
        'academic_session',
        'language_medium',
        'capacity',
        'advisor_id',
        'description',
        'notes',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean',
        'curriculum_type' => MalaysiaCurriculumType::class,
        'grade_level' => MalaysiaGradeLevel::class,
        'stream' => MalaysiaClassStream::class,
        'language_medium' => MalaysiaLanguageMedium::class,
        'capacity' => 'integer',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'class', 'code');
    }

    public function advisor()
    {
        return $this->belongsTo(Staff::class, 'advisor_id');
    }

    /**
     * Set curriculum_type - handle empty strings
     */
    protected function setCurriculumTypeAttribute($value)
    {
        // If value is empty string or null, use default
        $this->attributes['curriculum_type'] = ($value === '' || $value === null) ? 'kss' : $value;
    }

    /**
     * Set grade_level - handle empty strings
     */
    protected function setGradeLevelAttribute($value)
    {
        // If value is empty string or null, use default
        $this->attributes['grade_level'] = ($value === '' || $value === null) ? 'form_1' : $value;
    }

    /**
     * Set stream - handle empty strings
     */
    protected function setStreamAttribute($value)
    {
        // Stream is optional, so empty string becomes null
        $this->attributes['stream'] = ($value === '' || $value === null) ? null : $value;
    }

    /**
     * Set language_medium - handle empty strings
     */
    protected function setLanguageMediumAttribute($value)
    {
        // If value is empty string or null, use default
        $this->attributes['language_medium'] = ($value === '' || $value === null) ? 'malay' : $value;
    }
}

