<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class OpenAIAPIService{
    /**
     * @throws \Exception
     */
    public static function callApi($endPoint, $data){
        $openaiApiKey = config('services.openai.api_key');
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $openaiApiKey,
        ])
            ->post($endPoint, $data);
        if ($response->failed()) {
            throw new \Exception($response->json()['error']['message'] ?? 'An error occurred while calling the OpenAI API.');
        }
        return $response->json();
    }
}
