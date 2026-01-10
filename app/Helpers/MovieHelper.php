<?php

namespace App\Helpers;

class MovieHelper
{
    public static function formatDuration(?int $minutes): string
    {
        if (!$minutes || $minutes <= 0) {
            return '-';
        }

        if ($minutes < 60) {
            return "{%minutes} мин";
        }
        $hours = floor($minutes / 60);
        $remainingMinutes = $minutes % 60;

        if ($remainingMinutes === 0) {
            return "{$hours} ч";
        }
        return "{$hours} ч {$remainingMinutes} мин";
    }

    public static function formDurationShort(?int $minutes): string
    {
        if (!$minutes || $minutes <= 0) {
            return '-';
        }
        $hours = floor($minutes / 60);
        $remainingMinutes = $minutes % 60;

        return sprintf("%d:%02d", $hours, $remainingMinutes);

    }

    public static function formatDurationWithIcon(?int $minutes): string
    {
        if (!$minutes || $minutes <= 0) {
            return '<i class="far fa-clock"></i> —';
        }

        $formatted = self::formatDuration($minutes);
        return "<i class='far fa-clock'></i> {$formatted}";
    }
}
