<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Communication;
use App\Models\CommunicationTemplate;
use App\Services\Communication\CommunicationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use RealRashid\SweetAlert\Facades\Alert;
use Spatie\Permission\Models\Role;
use App\Models\AcademicClass;
use App\Models\Section;

class CommunicationController extends Controller
{
    protected $communicationService;

    public function __construct(CommunicationService $communicationService)
    {
        $this->communicationService = $communicationService;
    }

    public function logs(Request $request)
    {
        $logs = Communication::orderBy('created_at', 'desc')->paginate(50);
        return view('backend.pages.communications.logs', compact('logs'));
    }

    public function compose()
    {
        $roles = Role::whereNotIn('name', ['super-admin'])->get();
        $academicClasses = AcademicClass::all();
        $sections = Section::all();
        
        return view('backend.pages.communications.compose', compact('roles', 'academicClasses', 'sections'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'channel'         => 'required|in:sms,email,push',
            'subject'         => 'nullable|string|max:255',
            'message'         => 'required|string',
            'target_role'     => 'required|string',   // single radio
            'target_classes'  => 'nullable|array',
            'target_sections' => 'nullable|array',
        ]);

        $targetRole = strtolower($request->input('target_role'));
        $targetClasses  = $request->input('target_classes', []);
        $targetSections = $request->input('target_sections', []);
        $channel = $request->input('channel');
        $message = $request->input('message');
        $subject = $request->input('subject');

        $recipientsCount = 0;

        if ($targetRole === 'student') {
            $studentsQuery = \App\Models\Student::query();
            if (!empty($targetClasses)) {
                $studentsQuery->whereHas('enrollments', function ($q) use ($targetClasses, $targetSections) {
                    $q->whereIn('academic_class_id', $targetClasses);
                    if (!empty($targetSections)) {
                        $q->whereIn('section_id', $targetSections);
                    }
                });
            }
            $students = $studentsQuery->get();
            foreach ($students as $student) {
                // personalized message if placeholder present
                $parsedMessage = str_replace(
                    ['{name}', '{class}'],
                    [$student->full_name, $student->currentEnrollment?->academicClass?->name ?? ''],
                    $message
                );
                $this->communicationService->dispatch($channel, $student, $parsedMessage, $subject);
                $recipientsCount++;
            }
        } elseif (in_array($targetRole, ['guardian', 'parent'])) {
            if (!empty($targetClasses)) {
                $guardianIds = \App\Models\Student::whereHas('enrollments', function ($q) use ($targetClasses, $targetSections) {
                    $q->whereIn('academic_class_id', $targetClasses);
                    if (!empty($targetSections)) {
                        $q->whereIn('section_id', $targetSections);
                    }
                })->pluck('guardian_id')->filter()->unique();

                $guardians = \App\Models\Guardian::whereIn('id', $guardianIds)->get();
            } else {
                $guardians = \App\Models\Guardian::all();
            }

            foreach ($guardians as $guardian) {
                $parsedMessage = str_replace('{name}', $guardian->guardian_name ?? 'Parent', $message);
                $this->communicationService->dispatch($channel, $guardian, $parsedMessage, $subject);
                $recipientsCount++;
            }
        } elseif ($targetRole === 'teacher') {
            $staffMembers = \App\Models\Staff::where('status', 'active')->get();
            foreach ($staffMembers as $staff) {
                $parsedMessage = str_replace('{name}', $staff->full_name, $message);
                $this->communicationService->dispatch($channel, $staff, $parsedMessage, $subject);
                $recipientsCount++;
            }
        } else {
            // General role resolution via User
            $users = \App\Models\User::role($targetRole)->get();
            foreach ($users as $user) {
                $parsedMessage = str_replace('{name}', $user->name, $message);
                $this->communicationService->dispatch($channel, $user, $parsedMessage, $subject);
                $recipientsCount++;
            }
        }

        Log::info("Manual communication via {$channel} dispatched to {$recipientsCount} recipients (role={$targetRole})");

        Alert::success('Queued', "Message has been queued for {$recipientsCount} recipient(s).");
        return redirect()->route('admin.communications.logs');
    }

    public function templates()
    {
        // Seed default templates if none exist
        if (CommunicationTemplate::count() === 0) {
            $defaultTemplates = [
                [
                    'name' => 'Daily Attendance Absence Alert',
                    'type' => 'sms',
                    'subject' => null,
                    'body' => 'Dear Parent, your child {student_name} is marked ABSENT today ({date}). Please contact school administration if this is unexpected.',
                    'variables' => ['{student_name}', '{date}', '{class}'],
                    'is_active' => true,
                ],
                [
                    'name' => 'Fee Payment Due Reminder',
                    'type' => 'sms',
                    'subject' => null,
                    'body' => 'Dear Parent, fee payment of NPR {amount} for {student_name} (Class {class}) is due on {due_date}. Kindly clear the dues promptly.',
                    'variables' => ['{student_name}', '{class}', '{amount}', '{due_date}'],
                    'is_active' => true,
                ],
                [
                    'name' => 'Fee Payment Receipt Confirmation',
                    'type' => 'sms',
                    'subject' => null,
                    'body' => 'Dear Parent, we have received fee payment of NPR {amount} for {student_name}. Receipt No: {receipt_no}. Thank you!',
                    'variables' => ['{student_name}', '{amount}', '{receipt_no}'],
                    'is_active' => true,
                ],
                [
                    'name' => 'Exam Result Published Notice',
                    'type' => 'sms',
                    'subject' => null,
                    'body' => 'Dear Parent/Student, the examination results for {exam_name} have been published. Please check the student portal to view report card.',
                    'variables' => ['{student_name}', '{exam_name}'],
                    'is_active' => true,
                ],
                [
                    'name' => 'General School Notice (Email)',
                    'type' => 'email',
                    'subject' => 'Important Notice: {notice_title}',
                    'body' => 'Dear {name}, please be informed about the following notice: {notice_title}. Please visit the school portal for detailed instructions.',
                    'variables' => ['{name}', '{notice_title}'],
                    'is_active' => true,
                ],
            ];

            foreach ($defaultTemplates as $t) {
                CommunicationTemplate::create($t);
            }
        }

        $templates = CommunicationTemplate::orderBy('type')->orderBy('name')->get();
        return view('backend.pages.communications.templates', compact('templates'));
    }

    public function storeTemplate(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:sms,email,push',
            'subject' => 'nullable|string|max:255',
            'body' => 'required|string',
            'is_active' => 'nullable|boolean',
        ]);

        CommunicationTemplate::create([
            'name' => $request->name,
            'type' => $request->type,
            'subject' => $request->type === 'sms' ? null : $request->subject,
            'body' => $request->body,
            'is_active' => $request->has('is_active'),
        ]);

        Alert::success('Created', 'Communication template created successfully.');
        return back();
    }

    public function updateTemplate(Request $request, $id)
    {
        $template = CommunicationTemplate::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:sms,email,push',
            'subject' => 'nullable|string|max:255',
            'body' => 'required|string',
            'is_active' => 'nullable|boolean',
        ]);

        $template->update([
            'name' => $request->name,
            'type' => $request->type,
            'subject' => $request->type === 'sms' ? null : $request->subject,
            'body' => $request->body,
            'is_active' => $request->has('is_active'),
        ]);

        Alert::success('Updated', 'Communication template updated successfully.');
        return back();
    }

    public function destroyTemplate($id)
    {
        $template = CommunicationTemplate::findOrFail($id);
        $template->delete();

        Alert::success('Deleted', 'Communication template deleted successfully.');
        return back();
    }

    /**
     * AJAX: Return sections belonging to one or more classes.
     */
    public function getSectionsByClass(Request $request)
    {
        $classIds = $request->input('class_ids', []);
        if (empty($classIds)) {
            return response()->json([]);
        }

        $sections = Section::whereHas('academicClasses', function ($q) use ($classIds) {
            $q->whereIn('academic_classes.id', $classIds);
        })->get(['id', 'name']);

        return response()->json($sections);
    }

    public function settings()
    {
        $smsConfig = \App\Models\CommunicationConfig::where('channel', 'sms')->first();
        $emailConfig = \App\Models\CommunicationConfig::where('channel', 'email')->first();
        $pushConfig = \App\Models\CommunicationConfig::where('channel', 'push')->first();

        return view('backend.pages.communications.settings', compact('smsConfig', 'emailConfig', 'pushConfig'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'channel' => 'required|in:sms,email,push',
            'driver' => 'required|string',
            'is_active' => 'nullable|boolean',
            'config' => 'nullable|array',
        ]);

        \App\Models\CommunicationConfig::upsertFor(
            $request->channel,
            $request->driver,
            $request->config ?? [],
            $request->has('is_active')
        );

        Alert::success('Updated', ucfirst($request->channel) . ' settings have been updated.');
        return back();
    }

    public function testSms(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'test_message' => 'required|string|max:160',
        ]);

        $gateway = app(\App\Services\Communication\SmsGatewayInterface::class);
        $result = $gateway->send($request->phone, $request->test_message);

        if ($result) {
            Alert::success('Success', "Test SMS was successfully sent to {$request->phone}!");
        } else {
            Alert::error('Failed', "Failed to send test SMS. Check your gateway credentials, token, or error logs.");
        }

        return back();
    }
}
