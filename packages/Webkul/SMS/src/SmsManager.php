<?php

namespace Webkul\SMS;

use Illuminate\Support\Facades\Cache;
use Webkul\SMS\Drivers\Contracts\SmsDriver;
use Webkul\SMS\Drivers\Msg91Driver;
use Webkul\SMS\Drivers\TwilioDriver;
use Webkul\SMS\Drivers\VonageDriver;
use Webkul\SMS\Repositories\SmsLogRepository;

class SmsManager
{
    /**
     * Number of minutes a one-time-password stays valid for.
     */
    protected const OTP_TTL_MINUTES = 5;

    /**
     * Create a new instance.
     */
    public function __construct(protected SmsLogRepository $smsLogRepository) {}

    /**
     * Resolve the currently active gateway driver.
     */
    public function driver(?string $gateway = null): SmsDriver
    {
        $gateway ??= $this->activeGateway();

        return match ($gateway) {
            'vonage' => app(VonageDriver::class),
            'msg91'  => app(Msg91Driver::class),
            default  => app(TwilioDriver::class),
        };
    }

    /**
     * Get the gateway currently selected in the admin configuration.
     */
    public function activeGateway(): string
    {
        return core()->getConfigData('sms.general.settings.active_gateway') ?: 'twilio';
    }

    /**
     * Send an SMS message through the active gateway and log the attempt.
     */
    public function send(?string $to, string $message, ?string $event = null): bool
    {
        if (
            ! $to
            || ! core()->getConfigData('sms.general.settings.enabled')
        ) {
            return false;
        }

        $gateway = $this->activeGateway();

        $result = $this->driver($gateway)->send($to, $message);

        $this->smsLogRepository->create([
            'gateway'   => $gateway,
            'event'     => $event,
            'recipient' => $to,
            'message'   => $message,
            'status'    => $result['status'],
            'response'  => $result['response'] ?? null,
        ]);

        return $result['status'] === 'sent';
    }

    /**
     * Generate a one-time-password, cache it, and send it to the given number.
     */
    public function generateAndSendOtp(string $to, string $cacheKey): bool
    {
        $otp = (string) random_int(100000, 999999);

        Cache::put($cacheKey, $otp, now()->addMinutes(self::OTP_TTL_MINUTES));

        return $this->send(
            $to,
            trans('admin::app.sms.two-factor.otp-message', ['code' => $otp]),
            'two_factor'
        );
    }

    /**
     * Verify a previously sent one-time-password.
     */
    public function verifyOtp(string $cacheKey, string $code): bool
    {
        $cachedOtp = Cache::get($cacheKey);

        if (
            ! $cachedOtp
            || ! hash_equals((string) $cachedOtp, $code)
        ) {
            return false;
        }

        Cache::forget($cacheKey);

        return true;
    }
}
