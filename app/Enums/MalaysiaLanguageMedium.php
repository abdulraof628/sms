<?php

namespace App\Enums;

enum MalaysiaLanguageMedium: string
{
    case Malay = 'malay';
    case English = 'english';
    case Chinese = 'chinese';
    case Tamil = 'tamil';
    case Bilingual = 'bilingual';
    case English_Malay = 'english_malay';
    
    public function getLabel(): string
    {
        return match($this) {
            self::Malay => 'Bahasa Melayu (Malay)',
            self::English => 'English',
            self::Chinese => 'Mandarin (中文)',
            self::Tamil => 'Tamil (தமிழ்)',
            self::Bilingual => 'Bilingual',
            self::English_Malay => 'English - Malay',
        };
    }
}
