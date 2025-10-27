<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Branch extends Model
{
    use HasFactory, HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'name',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'notes',
        'website',
        'principal_id',
        'established_year',
        'description',
        'status'
    ];

    protected $casts = [
        'established_year' => 'integer',
        'status' => 'boolean'
    ];

    public function principal()
    {
        return $this->belongsTo(User::class, 'principal_id');
    }

    public function classes()
    {
        return $this->hasMany(BranchClass::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }
}

