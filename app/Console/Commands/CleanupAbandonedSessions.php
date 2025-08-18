<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\OnboardingSession;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CleanupAbandonedSessions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cleanup-abandoned-sessions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes the onboarding sessions that are older than 24 hours and not completed.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("starting cleanup of abandoned onboarding sessions...");
        $cutoff = Carbon::now()->subHours(24); //  <--- means 24 hours ago

        $count = OnboardingSession::where('is_complete', false)
            ->where('updated_at', '<', $cutoff)
            ->delete();

        if ($count > 0) {
            $message = "deleted {$count} incomplete onboarding sessions older than 24 hours.";
            $this->info($message);
            Log::info("Schedule cleaup: " . $message);
        } else {
            $this->info("No abandoned/incomplete onboarding sessions found older than 24 hours.");
        }
        return self::SUCCESS;  //self::sucess = 0
    }
}
