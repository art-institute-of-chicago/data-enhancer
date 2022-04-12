<?php

namespace App\Enums;

enum TermType: string
{
    case CLASSIFICATION = 'TT-1';
    case MATERIAL = 'TT-2';
    case TECHNIQUE = 'TT-3';
    case STYLE = 'TT-4';
    case SUBJECT = 'TT-5';
    case DEPARTMENT = 'CT-1';
    case THEME = 'CT-3';

    public function display(): string
    {
        return match ($this)
        {
            self::CLASSIFICATION => 'classification',
            self::MATERIAL => 'material',
            self::TECHNIQUE => 'technique',
            self::STYLE => 'style',
            self::SUBJECT => 'subject',
            self::DEPARTMENT => 'department',
            self::THEME => 'theme',
        };
    }

    public static function fromDisplay($value): TermType
    {
        return match ($value)
        {
            'classification' => self::CLASSIFICATION,
            'material' => self::MATERIAL,
            'technique' => self::TECHNIQUE,
            'style' => self::STYLE,
            'subject' => self::SUBJECT,
            'department' => self::DEPARTMENT,
            'theme' => self::THEME,
        };
    }

    public static function random(): TermType
    {
        $cases = static::cases();

        return $cases[array_rand($cases)];
    }
}
