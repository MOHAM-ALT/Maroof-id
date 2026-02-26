<?php

namespace App\Console\Commands;

use App\Mail\CampaignMail;
use App\Models\EmailCampaign;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendEmailCampaign extends Command
{
    protected $signature = 'mail:send-campaign {campaign_id}';
    protected $description = 'Send an email campaign to its target audience';

    public function handle(): int
    {
        $campaign = EmailCampaign::findOrFail($this->argument('campaign_id'));

        if ($campaign->status === 'sent') {
            $this->error('Campaign already sent.');
            return self::FAILURE;
        }

        $campaign->update(['status' => 'sending']);

        $query = User::query();

        if ($campaign->target_audience !== 'all') {
            $roleName = match ($campaign->target_audience) {
                'customers' => 'customer',
                'partners' => 'print_partner',
                'resellers' => 'reseller',
                'designers' => 'designer',
                'affiliates' => 'affiliate',
                default => 'customer',
            };
            $query->whereHas('roles', fn ($q) => $q->where('name', $roleName));
        }

        $users = $query->get();
        $campaign->update(['recipients_count' => $users->count()]);

        $sent = 0;
        foreach ($users as $user) {
            if ($user->email) {
                Mail::to($user->email)->queue(new CampaignMail($campaign, $user->name));
                $sent++;
            }
        }

        $campaign->update([
            'status' => 'sent',
            'sent_at' => now(),
            'sent_count' => $sent,
        ]);

        $this->info("Campaign sent to {$sent} recipients.");

        return self::SUCCESS;
    }
}
