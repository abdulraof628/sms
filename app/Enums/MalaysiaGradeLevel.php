<?php

namespace App\Enums;

enum MalaysiaGradeLevel: string
{
    // Primary Education (Tahun 1-6)
    case Year1 = 'year_1';
    case Year2 = 'year_2';
    case Year3 = 'year_3';
    case Year4 = 'year_4';
    case Year5 = 'year_5';
    case Year6 = 'year_6';
    
    // Lower Secondary (Form 1-3 / Tingkatan 1-3)
    case Form1 = 'form_1';
    case Form2 = 'form_2';
    case Form3 = 'form_3';
    
    // Upper Secondary (Form 4-5 / Tingkatan 4-5)
    case Form4 = 'form_4';
    case Form5 = 'form_5';
    
    // Pre-University (Form 6 / Tingkatan 6)
    case Form6 = 'form_6';
    
    public function getLabel(): string
    {
        return match($this) {
            // Primary
            self::Year1 => 'Year 1 (Tahun 1)',
            self::Year2 => 'Year 2 (Tahun 2)',
            self::Year3 => 'Year 3 (Tahun 3)',
            self::Year4 => 'Year 4 (Tahun 4)',
            self::Year5 => 'Year 5 (Tahun 5)',
            self::Year6 => 'Year 6 (Tahun 6)',
            // Secondary
            self::Form1 => 'Form 1 (Tingkatan 1)',
            self::Form2 => 'Form 2 (Tingkatan 2)',
            self::Form3 => 'Form 3 (Tingkatan 3)',
            self::Form4 => 'Form 4 (Tingkatan 4)',
            self::Form5 => 'Form 5 (Tingkatan 5)',
            self::Form6 => 'Form 6 (Tingkatan 6)',
        };
    }
}
