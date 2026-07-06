<?php

namespace Webkul\SMS\Drivers;

use Illuminate\Support\Facades\Http;
use Webkul\SMS\Drivers\Contracts\SmsDriver;

class TwilioDriver implements SmsDriver
{
    /**
     * Send an SMS message via the Twilio REST API.
     */
    public function send(string $to, string $message): array
    {
        $accountSid = core()->getConfigData('sms.gateways.twilio.account_sid');

        $authToken = core()->getConfigData('sms.gateways.twilio.auth_token');

        $fromNumber = core()->getConfigData('sms.gateways.twilio.from_number');

        if (! $accountSid || ! $authToken || ! $fromNumber) {
            return [
                'status'   => 'failed',
                'response' => 'Twilio gateway is not configured.',
            ];
        }

        try {
            $response = Http::asForm()
                ->withBasicAuth($accountSid, $authToken)
                ->post("https://api.twilio.com/2010-04-01/Accounts/{$accountSid}/Messages.json", [
                    'To'   => $to,
                    'From' => $fromNumber,
                    'Body' => $message,
                ]);

            return [
                'status'   => $response->successful() ? 'sent' : 'failed',
                'response' => $response->body(),
            ];
        } catch (\Exception $e) {
            return [
                'status'   => 'failed',
                'response' => $e->getMessage(),
            ];
        }
    }
}
