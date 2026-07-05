<?php

namespace Webkul\SMS\Drivers\Contracts;

interface SmsDriver
{
    /**
     * Send an SMS message and return the outcome.
     *
     * @return array{status: string, response: string}
     */
    public function send(string $to, string $message): array;
}
