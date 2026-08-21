<?php

namespace App\Services\Communication;

use App\Jobs\SendSmsJob;
use App\Jobs\SendEmailJob;
use App\Jobs\SendPushJob;
use Illuminate\Support\Facades\DB;

class CommunicationService
{
    /**
     * Send a message to a user or external contact via the specified channel.
     *
     * @param string $type sms, email, push
     * @param mixed $recipient User model or email/phone string
     * @param string $message The body of the message
     * @param string|null $subject The subject (for email/push)
     * @return void
     */
    public function dispatch(string $type, $recipient, string $message, ?string $subject = null): void
    {
        $recipientId = is_object($recipient) ? $recipient->id : null;
        $recipientType = is_object($recipient) ? get_class($recipient) : null;
        $contactInfo = is_object($recipient) ? $this->extractContactInfo($recipient, $type) : $recipient;

        // Log the communication intent in the database
        $logId = DB::table('communications')->insertGetId([
            'type' => $type,
            'subject' => $subject,
            'message' => $message,
            'recipient_id' => $recipientId,
            'recipient_type' => $recipientType,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        if (!$contactInfo) {
            DB::table('communications')->where('id', $logId)->update([
                'status' => 'failed',
                'error_message' => 'No contact information available for recipient.',
                'updated_at' => now(),
            ]);
            return;
        }

        // Dispatch to appropriate queue job
        match ($type) {
            'sms' => SendSmsJob::dispatch($logId, $contactInfo, $message),
            'email' => SendEmailJob::dispatch($logId, $contactInfo, $subject, $message),
            'push' => SendPushJob::dispatch($logId, $contactInfo, $subject, $message),
            default => throw new \InvalidArgumentException("Invalid communication type: {$type}"),
        };
    }

    private function extractContactInfo($recipient, string $type)
    {
        if ($recipient instanceof \App\Models\Guardian) {
            return match ($type) {
                'sms' => $recipient->guardian_phone ?? $recipient->father_phone ?? $recipient->mother_phone ?? null,
                'email' => $recipient->guardian_email ?? null,
                'push' => $recipient->fcm_token ?? $recipient->device_token ?? null,
                default => null,
            };
        }

        if ($recipient instanceof \App\Models\Student) {
            $guardian = $recipient->guardian;
            if ($guardian) {
                return match ($type) {
                    'sms' => $guardian->guardian_phone ?? $guardian->father_phone ?? $guardian->mother_phone ?? null,
                    'email' => $guardian->guardian_email ?? null,
                    'push' => $guardian->fcm_token ?? $guardian->device_token ?? null,
                    default => null,
                };
            }
        }

        return match ($type) {
            'sms' => $recipient->phone ?? $recipient->mobile_number ?? null,
            'email' => $recipient->email ?? null,
            'push' => $recipient->fcm_token ?? $recipient->device_token ?? null,
            default => null,
        };
    }
}
