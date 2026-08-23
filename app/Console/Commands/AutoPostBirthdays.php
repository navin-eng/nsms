<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Student;
use App\Models\Staff;
use App\Models\SocialMediaSetting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class AutoPostBirthdays extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'birthday:autopost';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically post birthday wishes to configured social media platforms.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today();
        
        $students = Student::where('status', 'Active')
            ->where('consent_birthday_post', true)
            ->whereNotNull('dob')
            ->get()
            ->filter(function($student) use ($today) {
                $dob = Carbon::parse($student->dob);
                return $dob->month == $today->month && $dob->day == $today->day;
            });
            
        $staff = Staff::where('status', 'Active')
            ->where('consent_birthday_post', true)
            ->whereNotNull('dob')
            ->get()
            ->filter(function($member) use ($today) {
                $dob = Carbon::parse($member->dob);
                return $dob->month == $today->month && $dob->day == $today->day;
            });

        $birthdays = $students->merge($staff);

        if ($birthdays->isEmpty()) {
            $this->info('No birthdays today to post.');
            return Command::SUCCESS;
        }

        $activePlatforms = SocialMediaSetting::where('is_active', true)->get();

        if ($activePlatforms->isEmpty()) {
            $this->info('No active social media platforms configured.');
            return Command::SUCCESS;
        }

        foreach ($birthdays as $person) {
            $name = $person->first_name . ' ' . $person->last_name;
            $this->info("Processing birthday post for {$name}");

            foreach ($activePlatforms as $platform) {
                $message = $platform->custom_message_template 
                    ?? "Wishing a very Happy Birthday to {$name}! Have a wonderful day!";
                
                $message = str_replace('{name}', $name, $message);

                try {
                    $this->postToPlatform($platform, $message);
                    $this->info("Successfully posted to {$platform->platform} for {$name}");
                } catch (\Exception $e) {
                    $this->error("Failed to post to {$platform->platform} for {$name}: " . $e->getMessage());
                    Log::error("Social Media Auto-Post Error [{$platform->platform}]: " . $e->getMessage());
                }
            }
        }
        
        return Command::SUCCESS;
    }

    private function postToPlatform($platform, $message)
    {
        // Mock implementation of API logic based on platform
        if ($platform->platform === 'facebook') {
            if (!$platform->access_token || !$platform->page_id) {
                throw new \Exception("Missing Facebook credentials.");
            }
            /*
            $response = Http::post("https://graph.facebook.com/v17.0/{$platform->page_id}/feed", [
                'message' => $message,
                'access_token' => $platform->access_token,
            ]);
            if (!$response->successful()) {
                throw new \Exception("Facebook API Error: " . $response->body());
            }
            */
            Log::info("MOCK: Posted to Facebook. Message: {$message}");
        } elseif ($platform->platform === 'twitter') {
            if (!$platform->api_key || !$platform->api_secret) {
                throw new \Exception("Missing Twitter credentials.");
            }
            Log::info("MOCK: Posted to Twitter. Message: {$message}");
        } elseif ($platform->platform === 'instagram') {
            Log::info("MOCK: Posted to Instagram. Message: {$message}");
        }
    }
}
