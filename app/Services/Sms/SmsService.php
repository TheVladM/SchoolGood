<?php

namespace App\Services\Sms;

use App\Models\SmsLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    public function send(string $to, string $message): bool
    {
        $to = $this->normalizePhone($to);

        if (! config('sms.enabled')) {
            return $this->logOnly($to, $message, 'disabled');
        }

        $driver = config('sms.driver', 'log');

        return match ($driver) {
            'africas_talking' => $this->sendAfricasTalking($to, $message),
            'twilio' => $this->sendTwilio($to, $message),
            default => $this->logOnly($to, $message, $driver),
        };
    }

    private function logOnly(string $to, string $message, string $driver): bool
    {
        SmsLog::create([
            'to' => $to,
            'message' => $message,
            'driver' => $driver,
            'status' => 'logged',
        ]);

        Log::info('SMS (log driver)', ['to' => $to, 'message' => $message]);

        return true;
    }

    private function sendAfricasTalking(string $to, string $message): bool
    {
        $username = config('sms.africas_talking.username');
        $apiKey = config('sms.africas_talking.api_key');

        if (! $username || ! $apiKey) {
            return $this->logOnly($to, $message, 'africas_talking_missing_config');
        }

        $response = Http::withHeaders([
            'apiKey' => $apiKey,
            'Accept' => 'application/json',
        ])->asForm()->post('https://api.africastalking.com/version1/messaging', [
            'username' => $username,
            'to' => $to,
            'message' => $message,
            'from' => config('sms.africas_talking.from', 'SchoolGood'),
        ]);

        $ok = $response->successful();

        SmsLog::create([
            'to' => $to,
            'message' => $message,
            'driver' => 'africas_talking',
            'status' => $ok ? 'sent' : 'failed',
            'meta' => ['response' => $response->json()],
        ]);

        return $ok;
    }

    private function sendTwilio(string $to, string $message): bool
    {
        $sid = config('sms.twilio.sid');
        $token = config('sms.twilio.token');
        $from = config('sms.twilio.from');

        if (! $sid || ! $token || ! $from) {
            return $this->logOnly($to, $message, 'twilio_missing_config');
        }

        $response = Http::asForm()
            ->withBasicAuth($sid, $token)
            ->post("https://api.twilio.com/2010-04-01/Accounts/{$sid}/Messages.json", [
                'From' => $from,
                'To' => '+'.$to,
                'Body' => $message,
            ]);

        $ok = $response->successful();

        SmsLog::create([
            'to' => $to,
            'message' => $message,
            'driver' => 'twilio',
            'status' => $ok ? 'sent' : 'failed',
            'meta' => ['response' => $response->json()],
        ]);

        return $ok;
    }

    private function normalizePhone(string $phone): string
    {
        $digits = preg_replace('/\D+/', '', $phone) ?? '';
        $code = (string) config('sms.default_country_code', '237');

        if (str_starts_with($digits, $code)) {
            return $digits;
        }

        if (str_starts_with($digits, '0')) {
            return $code.ltrim($digits, '0');
        }

        return $code.$digits;
    }
}
