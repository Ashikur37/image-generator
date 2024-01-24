<?php

namespace App\Jobs;

use App\Models\Image;
use App\Services\ImageService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Illuminate\Support\Facades\Log;
use Exception;
use Throwable;

class GenerateImageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    /**
     * Create a new job instance.
     */
    public function __construct(public Image $image)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
            Log::info("Image generation start for image id: ".$this->image->id);
            //url of the image generated by the AI
            $generatedImageUrl = ImageService::generate($this->image->prompt);
            $this->image->updateRemoteUrl($generatedImageUrl);
            Log::info("Remote url-----------".$generatedImageUrl);
    }
    public function failed($exception): void
    {
        $this->image->fail($exception->getMessage());
    }
}
