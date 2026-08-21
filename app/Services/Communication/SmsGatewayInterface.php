<?php

namespace App\Services\Communication;

interface SmsGatewayInterface
{
    /**
     * Send an SMS to a phone number.
     *
     * @param string $phone
     * @param string $message
     * @return bool
     */
    public function send(string $phone, string $message): bool;
}
