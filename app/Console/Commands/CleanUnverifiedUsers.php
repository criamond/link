<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class CleanUnverifiedUsers extends Command
{
    protected $signature = 'users:clean-unverified';
    protected $description = 'Remove users who have not verified their email within the configured time period.';

    public function handle()
    {
        $days = config('auth.unverified_user_lifetime', 10);

        $cutoffDate = Carbon::now()->subDays($days);

        $deletedCount = User::whereNull('email_verified_at')
            ->where('created_at', '<', $cutoffDate)
            ->delete();

        $this->info("Deleted {$deletedCount} unverified users.");
    }
}

