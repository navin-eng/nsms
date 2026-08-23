<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventParticipant extends Model
{
    protected $fillable = [
        'event_id',
        'participant_type',
        'participant_id',
        'registered_at',
        'status',
        'certificate_issued',
        'certificate_id',
        'notes',
    ];

    protected $casts = [
        'registered_at'      => 'datetime',
        'certificate_issued' => 'boolean',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function certificate()
    {
        return $this->belongsTo(Certificate::class);
    }

    /**
     * Get the actual participant (Student or Staff) dynamically.
     */
    public function getParticipantAttribute()
    {
        if ($this->participant_type === 'student') {
            return Student::find($this->participant_id);
        }
        return Staff::find($this->participant_id);
    }

    /**
     * Scope: only students
     */
    public function scopeStudents($query)
    {
        return $query->where('participant_type', 'student');
    }

    /**
     * Scope: only staff
     */
    public function scopeStaff($query)
    {
        return $query->where('participant_type', 'staff');
    }

    public function getFullNameAttribute(): string
    {
        $p = $this->participant;
        return $p ? $p->first_name . ' ' . $p->last_name : '—';
    }

    public function getParticipantIdCodeAttribute(): string
    {
        $p = $this->participant;
        if (!$p) return '—';
        if ($this->participant_type === 'student') {
            return $p->student_id ?? $p->admission_no ?? '';
        }
        return $p->employee_id ?? '';
    }
}
