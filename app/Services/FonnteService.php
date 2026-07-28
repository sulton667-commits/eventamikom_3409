<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteService
{
    public static function sendMessage($phone, $message)
    {
        $token = env('FONNTE_TOKEN');
        if (!$token || $token === 'dummy-token-123') {
            Log::info("WA Dummy to $phone: $message");
            return true;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->post('https://api.fonnte.com/send', [
                'target' => $phone,
                'message' => $message,
                'countryCode' => '62',
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Fonnte Error: ' . $e->getMessage());
            return false;
        }
    }
}
