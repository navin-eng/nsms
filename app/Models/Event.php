<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'event_type',
        'category',
        'visit_date',
        'start_time',
        'end_time',
        'end_date',
        'venue',
        'result_link',
        'description',
        'image',
        'gallery',
        'status',
        'registration_open',
        'registration_deadline',
        'max_participants',
    ];

    protected $casts = [
        'gallery'               => 'array',
        'registration_open'     => 'boolean',
        'visit_date'            => 'date',
        'end_date'              => 'date',
        'registration_deadline' => 'date',
    ];

    public function participants()
    {
        return $this->hasMany(EventParticipant::class);
    }

    public function registeredStudents()
    {
        return $this->hasMany(EventParticipant::class)
                    ->where('participant_type', 'student')
                    ->whereIn('status', ['registered', 'attended']);
    }

    public function registeredStaff()
    {
        return $this->hasMany(EventParticipant::class)
                    ->where('participant_type', 'staff')
                    ->whereIn('status', ['registered', 'attended']);
    }

    public function getEventTypeLabelAttribute()
    {
        return match ($this->event_type) {
            'holiday'  => 'Holiday',
            'exam'     => 'Exam',
            'test'     => 'Test',
            'cca_eca'  => 'CCA / ECA',
            'result'   => 'Result',
            default    => 'Event',
        };
    }

    public function getCategoryLabelAttribute()
    {
        return match ($this->category) {
            'sports'    => 'Sports',
            'cultural'  => 'Cultural',
            'academic'  => 'Academic',
            'seminar'   => 'Seminar',
            'workshop'  => 'Workshop',
            'health'    => 'Health & Wellness',
            default     => 'Other',
        };
    }

    public function getIsUpcomingAttribute(): bool
    {
        return $this->visit_date >= now()->toDateString();
    }

    public function getIsPastAttribute(): bool
    {
        $endDate = $this->end_date ?? $this->visit_date;
        return $endDate < now()->toDateString();
    }

    public function isRegistrationFull(): bool
    {
        if (!$this->max_participants) return false;
        return $this->participants()->whereIn('status', ['registered', 'attended'])->count() >= $this->max_participants;
    }

    public function canRegister(): bool
    {
        if (!$this->registration_open) return false;
        if ($this->registration_deadline && $this->registration_deadline < now()->toDateString()) return false;
        if ($this->isRegistrationFull()) return false;
        return true;
    }
}
