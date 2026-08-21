<?php

namespace App\Services\Communication\Gateways;

use App\Services\Communication\SmsGatewayInterface;
use Illuminate\Support\Facades\Log;

class DummySmsGateway implements SmsGatewayInterface
{
    public function send(string $phone, string $message): bool
    {
        Log::info("Mock SMS sent to {$phone}: {$message}");
        return true;
    }
}
