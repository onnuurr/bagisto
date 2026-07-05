<?php

namespace Webkul\SMS\Drivers;

use Illuminate\Support\Facades\Http;
use Webkul\SMS\Drivers\Contracts\SmsDriver;

class VerimorDriver implements SmsDriver
{
    /**
     * Send an SMS message via the Verimor API.
     */
    public function send(string $to, string $message): array
    {
        $username = core()->getConfigData('sms.gateways.verimor.username');

        $password = core()->getConfigData('sms.gateways.verimor.password');

        $sourceAddr = core()->getConfigData('sms.gateways.verimor.source_addr');

        if (! $username || ! $password) {
            return [
                'status'   => 'failed',
                'response' => 'Verimor gateway is not configured.',
            ];
        }

        $payload = [
            'username' => $username,
            'password' => $password,
            'messages' => [
                [
                    'dest' => ltrim($to, '+'),
                    'msg'  => $message,
                ],
            ],
        ];

        if ($sourceAddr) {
            $payload['source_addr'] = $sourceAddr;
        }

        try {
            $response = Http::asJson()->post('https://sms.verimor.com.tr/v2/send.json', $payload);

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
