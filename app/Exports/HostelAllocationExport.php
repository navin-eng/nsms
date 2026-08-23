<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class HostelAllocationExport implements FromCollection, WithHeadings, WithMapping
{
    protected $allocations;

    public function __construct(Collection $allocations)
    {
        $this->allocations = $allocations;
    }

    public function collection()
    {
        return $this->allocations;
    }

    public function headings(): array
    {
        return [
            'Student ID',
            'Student Name',
            'Hostel',
            'Room No.',
            'Room Type',
            'Bed No.',
            'Allocated Date',
            'Status',
        ];
    }

    public function map($allocation): array
    {
        return [
            $allocation->student->registration_number ?? '-',
            $allocation->student->first_name . ' ' . $allocation->student->last_name,
            $allocation->bed->room->hostel->name ?? '-',
            $allocation->bed->room->room_number ?? '-',
            $allocation->bed->room->room_type ?? '-',
            $allocation->bed->bed_number ?? '-',
            $allocation->allocation_date,
            $allocation->status,
        ];
    }
}
