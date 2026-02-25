<?php

namespace App\Enums;

enum PayoutStatus: string
{
    case Pending = 'pending';
    case Processing = 'processing';
    case Completed = 'completed';
    case Failed = 'failed';

    public function label(): string
    {
        return match($this) {
            self::Pending => 'قيد الانتظار',
            self::Processing => 'قيد المعالجة',
            self::Completed => 'مكتمل',
            self::Failed => 'فشل',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Pending => 'warning',
            self::Processing => 'primary',
            self::Completed => 'success',
            self::Failed => 'danger',
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
