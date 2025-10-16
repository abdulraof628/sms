<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class School extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'name',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'notes',
        'phone',
        'email',
        'website',
        'principal_name',
        'established_year',
        'description',
        'status'
    ];

    protected $casts = [
        'established_year' => 'integer',
        'status' => 'boolean'
    ];

    public function classes()
    {
        return $this->hasMany(SchoolClass::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }
} 