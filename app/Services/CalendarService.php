<?php

namespace App\Services;

use App\Models\SiteSetting;
use Carbon\Carbon;
use Pratiksh\Nepalidate\Services\NepaliDate;
use Pratiksh\Nepalidate\Services\EnglishDate;

class CalendarService
{
    /**
     * Get the current active calendar system (BS or AD).
     */
    public function system(): string
    {
        return SiteSetting::current()->calendar_system ?? 'AD';
    }

    /**
     * Converts a database (AD) date to the system's preferred display format.
     */
    public function displayDate($adDate, $includeTime = false): string
    {
        if (!$adDate) return '';
        
        $carbon = Carbon::parse($adDate);
        $system = $this->system();
        
        if ($system === 'BS') {
            try {
                $bsDate = NepaliDate::create($carbon)->toBS();
                return $includeTime 
                    ? $bsDate . ' ' . $carbon->format('h:i A')
                    : $bsDate;
            } catch (\Exception $e) {
                // Fallback to AD if conversion fails (e.g. out of supported range)
                return $includeTime ? $carbon->format('Y-m-d h:i A') : $carbon->format('Y-m-d');
            }
        }
        
        return $includeTime ? $carbon->format('Y-m-d h:i A') : $carbon->format('Y-m-d');
    }

    /**
     * Converts a user-input string date (in BS or AD based on system setting) to a standard AD Carbon instance for DB storage.
     */
    public function toDbDate($inputDate): ?Carbon
    {
        if (empty($inputDate)) return null;

        $system = $this->system();

        if ($system === 'BS') {
            try {
                // Convert BS string back to AD
                $adDateString = EnglishDate::create($inputDate)->toAD();
                return Carbon::parse($adDateString);
            } catch (\Exception $e) {
                // If it fails to parse (maybe already AD format?), fallback
                return Carbon::parse($inputDate);
            }
        }

        return Carbon::parse($inputDate);
    }
}
