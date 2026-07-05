<?php

namespace Webkul\SMS\Drivers;

use Illuminate\Support\Facades\Http;
use Webkul\SMS\Drivers\Contracts\SmsDriver;

class VonageDriver implements SmsDriver
{
    /**
     * Send an SMS message via the Vonage (Nexmo) SMS API.
     */
    public function send(string $to, string $message): array
    {
        $apiKey = core()->getConfigData('sms.gateways.vonage.api_key');

        $apiSecret = core()->getConfigData('sms.gateways.vonage.api_secret');

        $fromNumber = core()->getConfigData('sms.gateways.vonage.from_number');

        if (! $apiKey || ! $apiSecret || ! $fromNumber) {
            return [
                'status'   => 'failed',
                'response' => 'Vonage gateway is not configured.',
            ];
        }

        try {
            $response = Http::asForm()->post('https://rest.nexmo.com/sms/json', [
                'api_key'    => $apiKey,
                'api_secret' => $apiSecret,
                'to'         => $to,
                'from'       => $fromNumber,
                'text'       => $message,
            ]);

            $status = $response->json('messages.0.status');

            return [
                'status'   => $response->successful() && $status === '0' ? 'sent' : 'failed',
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
