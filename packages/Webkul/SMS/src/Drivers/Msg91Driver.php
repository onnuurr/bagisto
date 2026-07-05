<?php

namespace Webkul\SMS\Drivers;

use Illuminate\Support\Facades\Http;
use Webkul\SMS\Drivers\Contracts\SmsDriver;

class Msg91Driver implements SmsDriver
{
    /**
     * Send an SMS message via the MSG91 HTTP API.
     */
    public function send(string $to, string $message): array
    {
        $authKey = core()->getConfigData('sms.gateways.msg91.auth_key');

        $senderId = core()->getConfigData('sms.gateways.msg91.sender_id');

        $route = core()->getConfigData('sms.gateways.msg91.route') ?: '4';

        if (! $authKey || ! $senderId) {
            return [
                'status'   => 'failed',
                'response' => 'MSG91 gateway is not configured.',
            ];
        }

        try {
            $response = Http::get('https://api.msg91.com/api/sendhttp.php', [
                'authkey'  => $authKey,
                'mobiles'  => ltrim($to, '+'),
                'message'  => $message,
                'sender'   => $senderId,
                'route'    => $route,
                'response' => 'json',
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
