<?php

namespace App\Enums;

enum ImageStatusEnum: string
{
    case NEW = 'new';
    case PROCESSING = 'processing';
    case COMPLETED = 'completed';
    case FAILED = 'failed';
    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}
