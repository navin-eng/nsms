<?php

namespace App\Services\Communication;

interface PushGatewayInterface
{
    /**
     * Send a push notification.
     *
     * @param array|string $tokens
     * @param string $title
     * @param string $body
     * @param array $data
     * @return bool
     */
    public function send($tokens, string $title, string $body, array $data = []): bool;
}
