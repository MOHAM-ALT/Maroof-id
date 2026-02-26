<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    /**
     * Send SMS message via Twilio
     *
     * @param string $to Phone number (e.g., +966501234567)
     * @param string $message Message content
     * @return bool
     */
    public function send(string $to, string $message): bool
    {
        $sid = config('services.twilio.sid');
        $token = config('services.twilio.token');
        $from = config('services.twilio.from');

        if (!$sid || !$token || !$from) {
            Log::error('Twilio credentials not set in environment.');
            return false;
        }

        try {
            $response = Http::withBasicAuth($sid, $token)
                ->asForm()
                ->post("https://api.twilio.com/2010-04-01/Accounts/{$sid}/Messages.json", [
                    'To' => $to,
                    'From' => $from,
                    'Body' => $message,
                ]);

            if ($response->successful()) {
                return true;
            }

            Log::error('Twilio SMS Failed: ' . $response->body());
            return false;
        } catch (\Exception $e) {
            Log::error('Twilio SMS Exception: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send WhatsApp message via Twilio
     *
     * @param string $to Phone number (e.g., +966501234567)
     * @param string $message Message content
     * @return bool
     */
    public function sendWhatsApp(string $to, string $message): bool
    {
        $sid = config('services.twilio.sid');
        $token = config('services.twilio.token');
        $from = config('services.twilio.whatsapp_from');

        if (!$sid || !$token || !$from) {
            Log::error('Twilio WhatsApp credentials not set in environment.');
            return false;
        }

        try {
            $response = Http::withBasicAuth($sid, $token)
                ->asForm()
                ->post("https://api.twilio.com/2010-04-01/Accounts/{$sid}/Messages.json", [
                    'To' => 'whatsapp:' . $to,
                    'From' => 'whatsapp:' . $from,
                    'Body' => $message,
                ]);

            if ($response->successful()) {
                return true;
            }

            Log::error('Twilio WhatsApp Failed: ' . $response->body());
            return false;
        } catch (\Exception $e) {
            Log::error('Twilio WhatsApp Exception: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send OTP verification code
     *
     * @param string $to Phone number
     * @param string $code Verification code
     * @return bool
     */
    public function sendOtp(string $to, string $code): bool
    {
        $message = "Your Maroof verification code is: {$code}";
        return $this->send($to, $message);
    }
}
