<?php

namespace App\Enums;

enum NotificationType: string
{
    case SMS = 'sms';
    case WHATSAPP = 'whatsapp';
    case EMAIL = 'email';
    case PUSH = 'push';

    /**
     * Get label for display
     */
    public function label(): string
    {
        return match($this) {
            self::SMS => 'SMS',
            self::WHATSAPP => 'WhatsApp',
            self::EMAIL => 'Email',
            self::PUSH => 'Push Notification',
        };
    }

    /**
     * Get all values
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
