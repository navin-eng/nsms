<?php

use App\Services\CalendarService;

if (!function_exists('system_date')) {
    /**
     * Helper to format a database date according to the system calendar setting (BS/AD).
     *
     * @param mixed $date The Carbon instance or string date to format.
     * @param bool $includeTime Whether to include the time in the output.
     * @return string
     */
    function system_date($date, $includeTime = false): string
    {
        if (empty($date)) return '';
        return app(CalendarService::class)->displayDate($date, $includeTime);
    }
}
