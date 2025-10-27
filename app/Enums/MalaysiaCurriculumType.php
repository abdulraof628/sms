<?php

namespace App\Enums;

enum MalaysiaCurriculumType: string
{
    case KurikulumMerdeka = 'kurikulum_merdeka';
    case KSS = 'kss'; // Kurikulum Skor Separuh
    case KSSM = 'kssm'; // Kurikulum Standard Sekolah Menengah
    case KBSR = 'kbsr'; // Kurikulum Bersepadu Sekolah Rendah
    case KBSM = 'kbsm'; // Kurikulum Bersepadu Sekolah Menengah
    case SPM = 'spm'; // Sijil Pelajaran Malaysia
    case STPM = 'stpm'; // Sijil Tinggi Pelajaran Malaysia
    case ModularSystem = 'modular';
    
    public function getLabel(): string
    {
        return match($this) {
            self::KurikulumMerdeka => 'Kurikulum Merdeka',
            self::KSS => 'KSS',
            self::KSSM => 'KSSM (Secondary)',
            self::KBSR => 'KBSR (Primary)',
            self::KBSM => 'KBSM (Secondary)',
            self::SPM => 'SPM (Exam)',
            self::STPM => 'STPM (Advanced)',
            self::ModularSystem => 'Modular System',
        };
    }
}
