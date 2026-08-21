<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendPushJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $logId;
    protected $tokens;
    protected $title;
    protected $message;

    public function __construct($logId, $tokens, $title, $message)
    {
        $this->logId = $logId;
        $this->tokens = $tokens;
        $this->title = $title ?? 'Notification';
        $this->message = $message;
    }

    public function handle(\App\Services\Communication\PushGatewayInterface $gateway): void
    {
        try {
            $success = $gateway->send($this->tokens, $this->title, $this->message);

            \Illuminate\Support\Facades\DB::table('communications')
                ->where('id', $this->logId)
                ->update([
                    'status' => $success ? 'sent' : 'failed',
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
