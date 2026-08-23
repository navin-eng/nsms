<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class HostelAttendanceExport implements FromCollection, WithHeadings, WithMapping
{
    protected $attendances;

    public function __construct(Collection $attendances)
    {
        $this->attendances = $attendances;
    }

    public function collection()
    {
        return $this->attendances;
    }

    public function headings(): array
    {
        return [
            'Date',
            'Student ID',
            'Student Name',
            'Hostel',
            'Status',
            'Remarks',
        ];
    }

    public function map($attendance): array
    {
        return [
            $attendance->date,
            $attendance->student->registration_number ?? '-',
            $attendance->student->first_name . ' ' . $attendance->student->last_name,
            $attendance->hostel->name ?? '-',
            $attendance->status,
            $attendance->remarks ?? '',
        ];
    }
}
