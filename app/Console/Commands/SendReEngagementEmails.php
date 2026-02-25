<?php

namespace App\Console\Commands;

use App\Mail\ReEngagementMail;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendReEngagementEmails extends Command
{
    protected $signature = 'mail:re-engagement';
    protected $description = 'Send re-engagement emails to inactive users (30+ days)';

    public function handle(): int
    {
        $users = User::where('updated_at', '<', now()->subDays(30))
            ->whereHas('roles', fn ($q) => $q->where('name', 'customer'))
            ->get();

        $sent = 0;
        foreach ($users as $user) {
            $daysSince = (int) $user->updated_at->diffInDays(now());
            Mail::to($user->email)->queue(new ReEngagementMail($user, $daysSince));
            $sent++;
        }

        $this->info("Sent {$sent} re-engagement emails.");

        return self::SUCCESS;
    }
}
