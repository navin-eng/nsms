<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\School;
use App\Models\ProviderAuditLog;
use Carbon\Carbon;

class CheckSchoolSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nsms:check-subscriptions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and update status of schools with expired subscriptions';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting subscription check...');

        $expiredSchools = School::where('status', 'active')
            ->whereNotNull('subscription_end')
            ->whereDate('subscription_end', '<', Carbon::today())
            ->get();

        $count = 0;
        foreach ($expiredSchools as $school) {
            $oldStatus = $school->status;
            $school->status = 'expired';
            $school->save();

            ProviderAuditLog::log(
                'school.expired_auto',
                $school,
                "Automated system marked school as expired because subscription_end ({$school->subscription_end}) passed.",
                ['status' => $oldStatus],
                ['status' => 'expired']
            );

            $this->line("Marked school ID {$school->id} ({$school->school_code}) as expired.");
            $count++;
        }

        $this->info("Subscription check complete. Expired {$count} schools.");
        return Command::SUCCESS;
    }
}
