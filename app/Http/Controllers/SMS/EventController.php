<?php

namespace App\Http\Controllers\SMS;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventParticipant;
use App\Models\Student;
use App\Models\Staff;
use App\Models\Certificate;
use App\Models\AcademicClass;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;

class EventController extends Controller
{
    // ─── Index ───────────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = Event::withCount(['participants as total_participants' => function ($q) {
            $q->whereIn('status', ['registered', 'attended']);
        }])->latest('visit_date');

        if ($request->filled('category'))  $query->where('category', $request->category);
        if ($request->filled('status')) {
            if ($request->status === 'upcoming') {
                $query->where('visit_date', '>=', now()->toDateString());
            } elseif ($request->status === 'past') {
                $query->where('visit_date', '<', now()->toDateString());
            }
        }
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $events = $query->paginate(12)->withQueryString();
        $today  = now()->toDateString();

        // Export
        if ($request->filled('export')) {
            $allEvents = Event::withCount(['participants as total_participants'])->get();
            if ($request->export === 'pdf') {
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('backend.pages.sms.events.pdf_list', compact('allEvents'));
                return $pdf->download('events_list.pdf');
            } elseif ($request->export === 'print') {
                return view('backend.pages.sms.events.pdf_list', compact('allEvents'));
            }
        }

        return view('backend.pages.sms.events.index', compact('events', 'today'));
    }

    // ─── Create ──────────────────────────────────────────────────────────────
    public function create()
    {
        return view('backend.pages.sms.events.create');
    }

    // ─── Store ───────────────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'                  => 'required|string|max:255',
            'event_type'            => 'required|in:holiday,exam,test,cca_eca,result,event',
            'category'              => 'required|in:sports,cultural,academic,seminar,workshop,health,other',
            'visit_date'            => 'required|date',
            'end_date'              => 'nullable|date|after_or_equal:visit_date',
            'start_time'            => 'nullable|date_format:H:i',
            'end_time'              => 'nullable|date_format:H:i',
            'venue'                 => 'nullable|string|max:255',
            'description'           => 'nullable|string',
            'image'                 => 'nullable|image|max:2048',
            'result_link'           => 'nullable|url',
            'registration_open'     => 'nullable|boolean',
            'registration_deadline' => 'nullable|date',
            'max_participants'      => 'nullable|integer|min:1',
            'status'                => 'required|in:0,1',
        ]);

        $data['slug']              = Str::slug($data['name']) . '-' . time();
        $data['registration_open'] = $request->boolean('registration_open');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('events', 'public');
        } else {
            $data['image'] = ''; // Fix SQLite NOT NULL constraint
        }

        if (!isset($data['description'])) {
            $data['description'] = '';
        }

        Event::create($data);
        Alert::success('Created', 'Event created successfully.');
        return redirect()->route('sms.events.index');
    }

    // ─── Show ─────────────────────────────────────────────────────────────────
    public function show(Request $request, Event $event)
    {
        $event->load(['participants']);
        $students  = Student::orderBy('first_name')->get();
        $staffList = Staff::where('status', 'active')->orWhereNull('status')->orderBy('first_name')->get();
        $classes   = AcademicClass::orderBy('level')->get();

        // Separate participants
        $studentParticipants = $event->participants->where('participant_type', 'student');
        $staffParticipants   = $event->participants->where('participant_type', 'staff');

        // Resolve actual model instances
        $studentIds = $studentParticipants->pluck('participant_id')->all();
        $staffIds   = $staffParticipants->pluck('participant_id')->all();
        $studentMap = Student::whereIn('id', $studentIds)->get()->keyBy('id');
        $staffMap   = Staff::whereIn('id', $staffIds)->get()->keyBy('id');

        // Export participants
        if ($request->filled('export')) {
            $setting = SiteSetting::first();
            if ($request->export === 'pdf') {
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('backend.pages.sms.events.pdf_participants', compact('event', 'studentParticipants', 'staffParticipants', 'studentMap', 'staffMap', 'setting'));
                return $pdf->download("event_{$event->id}_participants.pdf");
            } elseif ($request->export === 'print') {
                return view('backend.pages.sms.events.pdf_participants', compact('event', 'studentParticipants', 'staffParticipants', 'studentMap', 'staffMap', 'setting'));
            }
        }

        return view('backend.pages.sms.events.show', compact(
            'event', 'students', 'staffList', 'classes',
            'studentParticipants', 'staffParticipants',
            'studentMap', 'staffMap'
        ));
    }

    // ─── Edit ─────────────────────────────────────────────────────────────────
    public function edit(Event $event)
    {
        return view('backend.pages.sms.events.edit', compact('event'));
    }

    // ─── Update ───────────────────────────────────────────────────────────────
    public function update(Request $request, Event $event)
    {
        $data = $request->validate([
            'name'                  => 'required|string|max:255',
            'event_type'            => 'required|in:holiday,exam,test,cca_eca,result,event',
            'category'              => 'required|in:sports,cultural,academic,seminar,workshop,health,other',
            'visit_date'            => 'required|date',
            'end_date'              => 'nullable|date|after_or_equal:visit_date',
            'start_time'            => 'nullable|date_format:H:i',
            'end_time'              => 'nullable|date_format:H:i',
            'venue'                 => 'nullable|string|max:255',
            'description'           => 'nullable|string',
            'image'                 => 'nullable|image|max:2048',
            'result_link'           => 'nullable|url',
            'registration_open'     => 'nullable|boolean',
            'registration_deadline' => 'nullable|date',
            'max_participants'      => 'nullable|integer|min:1',
            'status'                => 'required|in:0,1',
        ]);

        $data['registration_open'] = $request->boolean('registration_open');

        if ($request->hasFile('image')) {
            if ($event->image) Storage::disk('public')->delete($event->image);
            $data['image'] = $request->file('image')->store('events', 'public');
        }

        if (array_key_exists('description', $data) && is_null($data['description'])) {
            $data['description'] = '';
        }

        $event->update($data);
        Alert::success('Updated', 'Event updated successfully.');
        return redirect()->route('sms.events.show', $event);
    }

    // ─── Destroy ──────────────────────────────────────────────────────────────
    public function destroy(Event $event)
    {
        if ($event->image) Storage::disk('public')->delete($event->image);
        $event->delete();
        Alert::success('Deleted', 'Event deleted.');
        return redirect()->route('sms.events.index');
    }

    // ─── Register Participant ─────────────────────────────────────────────────
    public function registerParticipant(Request $request, Event $event)
    {
        $request->validate([
            'participant_type' => 'required|in:student,staff',
            'participant_ids'  => 'required|array|min:1',
            'participant_ids.*'=> 'required|integer',
        ]);

        $type  = $request->participant_type;
        $ids   = $request->participant_ids;
        $added = 0;
        $skipped = 0;

        DB::beginTransaction();
        try {
            foreach ($ids as $id) {
                $exists = EventParticipant::where('event_id', $event->id)
                    ->where('participant_type', $type)
                    ->where('participant_id', $id)
                    ->whereIn('status', ['registered', 'attended'])
                    ->exists();

                if ($exists) { $skipped++; continue; }

                // Check capacity
                if ($event->isRegistrationFull()) break;

                EventParticipant::create([
                    'event_id'         => $event->id,
                    'participant_type' => $type,
                    'participant_id'   => $id,
                    'registered_at'    => now(),
                    'status'           => 'registered',
                ]);
                $added++;
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Alert::error('Error', $e->getMessage());
            return back();
        }

        $msg = "$added participant(s) registered.";
        if ($skipped) $msg .= " $skipped already registered (skipped).";
        Alert::success('Registered', $msg);
        return back();
    }

    // ─── Remove Participant ───────────────────────────────────────────────────
    public function removeParticipant(Event $event, EventParticipant $participant)
    {
        $participant->update(['status' => 'cancelled']);
        Alert::success('Removed', 'Participant removed from event.');
        return back();
    }

    // ─── Mark Attendance ──────────────────────────────────────────────────────
    public function markAttendance(Request $request, Event $event)
    {
        $request->validate([
            'attended_ids' => 'nullable|array',
            'attended_ids.*' => 'integer|exists:event_participants,id',
        ]);

        $attendedIds = $request->attended_ids ?? [];

        // Mark checked as attended, uncheck as registered
        EventParticipant::where('event_id', $event->id)
            ->whereIn('status', ['registered', 'attended'])
            ->each(function ($p) use ($attendedIds) {
                $p->update(['status' => in_array($p->id, $attendedIds) ? 'attended' : 'registered']);
            });

        Alert::success('Saved', 'Attendance marked successfully.');
        return back()->withFragment('attendance');
    }

    // ─── Issue Certificate ────────────────────────────────────────────────────
    public function issueCertificate(Event $event, EventParticipant $participant)
    {
        if ($participant->certificate_issued) {
            // Re-download existing
            $cert = Certificate::find($participant->certificate_id);
            if ($cert) {
                return redirect()->route('sms.certificates.print', $cert->id);
            }
        }

        // Only works for students (Certificate model is student-based)
        if ($participant->participant_type !== 'student') {
            Alert::error('Not Supported', 'Certificate issuance for staff is not yet supported through this flow.');
            return back();
        }

        $student = Student::findOrFail($participant->participant_id);
        $setting = SiteSetting::first();

        $cert = Certificate::create([
            'student_id'  => $student->id,
            'type'        => 'participation',
            'title'       => 'Certificate of Participation — ' . $event->name,
            'issue_date'  => now()->toDateString(),
            'metadata'    => [
                'event_name'  => $event->name,
                'event_date'  => $event->visit_date->format('d M Y'),
                'venue'       => $event->venue,
                'category'    => $event->category_label,
                'remarks'     => 'Participated in the above-mentioned event.',
            ],
            'status'      => 'issued',
            'issued_by'   => auth()->id(),
        ]);

        $participant->update([
            'certificate_issued' => true,
            'certificate_id'     => $cert->id,
        ]);

        Alert::success('Issued', "Certificate issued for {$student->first_name}.");
        return redirect()->route('sms.certificates.print', $cert->id);
    }
}
