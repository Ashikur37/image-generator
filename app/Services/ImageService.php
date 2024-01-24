<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Log;

class ImageService
{
    public static function generate(string $prompt): string
    {
        $output = OpenAIAPIService::callApi('https://api.openai.com/v1/images/generations', [
            'model' => 'dall-e-2',
            'prompt' => $prompt,
            'n' => 1,
            'size' => '512x512',
        ]);
        if (isset($output["error"])) {
            Log::error($output["error"]["message"] ?? "Something went wrong");
            throw new Exception($output["error"]["message"] ?? "Something went wrong");
        }
        return $output['data'][0]['url'] ?? "";
    }
}
