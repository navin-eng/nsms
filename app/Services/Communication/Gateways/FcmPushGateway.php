<?php

namespace App\Services\Communication\Gateways;

use App\Services\Communication\PushGatewayInterface;
use Illuminate\Support\Facades\Log;

class FcmPushGateway implements PushGatewayInterface
{
    public function send($tokens, string $title, string $body, array $data = []): bool
    {
        // Placeholder for Firebase Cloud Messaging implementation
        $tokensList = is_array($tokens) ? implode(', ', $tokens) : $tokens;
        Log::info("Mock FCM Push sent to [{$tokensList}]: {$title} - {$body}");
        return true;
    }
}
