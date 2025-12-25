<?php

namespace App\Enums;

enum BookingStatus: string
{
    case Upcoming  = 'Upcoming';
    case Completed = 'Completed';
    case Cancelled = 'Cancelled';

    case Rescheduled = 'Rescheduled';

    /**
     * القيم التي ستُستخدم في قاعدة البيانات
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }


}
