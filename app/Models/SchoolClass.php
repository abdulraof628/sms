<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class SchoolClass extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'school_id',
        'name',
        'code',
        'description',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean'
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'class', 'code');
    }
} 