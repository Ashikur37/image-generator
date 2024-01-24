<?php

namespace App\Jobs;

use App\Models\Image;
use App\Services\PromptService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GenerateImagePromptJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public Image $image)
    {

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
            Log::info("Prompt generation start for image id: ".$this->image->id);
            $prompt = PromptService::generateImagePrompt($this->image->keyword);
            $this->image->updatePrompt($prompt);
    }
    public function failed($exception): void
    {
        $this->image->fail($exception->getMessage());
    }
}
