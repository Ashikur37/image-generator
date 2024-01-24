<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Log;

class PromptService
{
    public static function generateImagePrompt(string $keyword): string
    {
        $context = "Write a 50 word prompt that will be used to generate an AI image. The image is about: ";
        $message = $context . $keyword;
        $output = OpenAIAPIService::callApi('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'user', 'content' => $message],
            ]
        ]);
        if (isset($output["error"])) {
            Log::error($output["error"]["message"] ?? "Something went wrong");
            throw new Exception($output["error"]["message"] ?? "Something went wrong");
        }
        return $output['choices'][0]['message']['content'];
    }
}
