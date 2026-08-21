<?php

namespace App\Services\Communication\Gateways;

use App\Services\Communication\SmsGatewayInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\CommunicationConfig;

class NtcSmsGateway implements SmsGatewayInterface
{
    public function send(string $phone, string $message): bool
    {
        $config = CommunicationConfig::activeFor('sms');
        
        if (!$config || $config->driver !== 'ntc' || empty($config->config['token'])) {
            Log::error('NTC SMS config missing or inactive.');
            return false;
        }

        $token = $config->config['token'];
        $identity = $config->config['identity'] ?? 'NTC_ALERT';
        $apiUrl = $config->config['api_url'] ?? 'https://sms.ntc.net.np/api/v1/send';

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json',
            ])->post($apiUrl, [
                'sender' => $identity,
                'recipient' => $phone,
                'message' => $message,
            ]);

            if ($response->successful()) {
                Log::info("NTC SMS sent successfully to {$phone}");
                return true;
            }

            Log::error("NTC SMS failed: " . $response->body());
            return false;
        } catch (\Exception $e) {
            Log::error("NTC SMS Exception: " . $e->getMessage());
            return false;
        }
    }
}
