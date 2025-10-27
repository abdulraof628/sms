<?php

namespace App\Enums;

enum MalaysiaClassStream: string
{
    case General = 'general';
    case Science = 'science';
    case Arts = 'arts';
    case Commerce = 'commerce';
    case Technical = 'technical';
    case Vocational = 'vocational';
    case Engineering = 'engineering';
    case Biology = 'biology';
    case AccountingFinance = 'accounting_finance';
    case HumanitiesSocial = 'humanities_social';
    
    public function getLabel(): string
    {
        return match($this) {
            self::General => 'General (Umum)',
            self::Science => 'Science (Sains)',
            self::Arts => 'Arts (Sastera)',
            self::Commerce => 'Commerce (Perdagangan)',
            self::Technical => 'Technical (Teknik)',
            self::Vocational => 'Vocational (Vokasional)',
            self::Engineering => 'Engineering (Kejuruteraan)',
            self::Biology => 'Biology (Biologi)',
            self::AccountingFinance => 'Accounting & Finance',
            self::HumanitiesSocial => 'Humanities & Social Studies',
        };
    }
}
