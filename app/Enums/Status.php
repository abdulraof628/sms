<?php

namespace App\Enums;

enum Status: int
{
    case Inactive = 0;
    case Active = 1;

    public function getLabel(): string
    {
        return match($this) {
            Status::Active => 'Active',
            Status::Inactive => 'Inactive',
        };
    }
}
