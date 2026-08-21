<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $logId;
    protected $email;
    protected $subject;
    protected $message;

    public function __construct($logId, $email, $subject, $message)
    {
        $this->logId = $logId;
        $this->email = $email;
        $this->subject = $subject ?? 'Notification';
        $this->message = $message;
    }

    public function handle(): void
    {
        try {
            // Placeholder: Replace with Mail::raw or Mail::send
            \Illuminate\Support\Facades\Log::info("Mock Email sent to {$this->email}: {$this->subject} - {$this->message}");

            \Illuminate\Support\Facades\DB::table('communications')
                ->where('id', $this->logId)
                ->update([
                    'status' => 'sent',
                    'updated_at' => now(),
                ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::table('communications')
                ->where('id', $this->logId)
                ->update([
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                    'updated_at' => now(),
                ]);
        }
    }
}
