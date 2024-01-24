<?php

namespace App\Jobs;

use App\Enums\ImageStatusEnum;
use App\Models\Image;
use App\Services\StorageService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class StoreImageJob implements ShouldQueue
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
        //path and the url of the image stored in aws s3
        $response = Http::timeout(300)->get($this->image->remote_url);

        if (!$response->successful()) {
            throw new Exception('Failed to download image from external source.');
        }

            $path =Str::slug($this->image->keyword) . uniqid() . ".jpg";
            $url = StorageService::storeFile($path, $response->body());
            $this->image->updateImageUrl($url);
    }
    public function failed($exception): void
    {
        $this->image->fail($exception->getMessage());
    }


}
