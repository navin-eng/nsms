<?php

namespace App\Services\Communication\Gateways;

use App\Services\Communication\SmsGatewayInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\CommunicationConfig;

class SparrowSmsGateway implements SmsGatewayInterface
{
    protected string $url = 'http://api.sparrowsms.com/v2/sms/';

    public function send(string $phone, string $message): bool
    {
        $config = CommunicationConfig::activeFor('sms');

        if (!$config || $config->driver !== 'sparrow' || empty($config->config['token']) || empty($config->config['identity'])) {
            Log::error('Sparrow SMS config missing or inactive.');
            return false;
        }

        $token = $config->config['token'];
        $identity = $config->config['identity'];

        try {
            $response = Http::get($this->url, [
                'token' => $token,
                'from' => $identity,
                'to' => $phone,
                'text' => $message,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['response_code']) && $data['response_code'] == 200) {
                    Log::info("Sparrow SMS sent successfully to {$phone}");
                    return true;
                } else {
                    Log::error("Sparrow SMS failed: " . $response->body());
                    return false;
                }
            }

            Log::error("Sparrow SMS HTTP Error: " . $response->status());
            return false;
        } catch (\Exception $e) {
            Log::error("Sparrow SMS Exception: " . $e->getMessage());
            return false;
        }
    }
}
