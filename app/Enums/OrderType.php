<?php

namespace App\Enums;

enum OrderType: string
{
    case PhysicalCard = 'physical_card';
    case DigitalOnly = 'digital_only';
    case CustomDesign = 'custom_design';
    case Bulk = 'bulk';

    public function label(): string
    {
        return match($this) {
            self::PhysicalCard => 'فيزيائية',
            self::DigitalOnly => 'رقمي',
            self::CustomDesign => 'مخصص',
            self::Bulk => 'جملة',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn ($case) => [$case->value => $case->label()])->toArray();
    }
}
