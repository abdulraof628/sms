<?php

namespace App\Enums;

enum StaffRole: string
{
    case Admin = 'admin';
    case Principal = 'principal';
    case Teacher = 'teacher';

    public function getLabel(): string
    {
        return match($this) {
            StaffRole::Admin => 'Admin',
            StaffRole::Principal => 'Principal',
            StaffRole::Teacher => 'Teacher',
        };
    }
}
