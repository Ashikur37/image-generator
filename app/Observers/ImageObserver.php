<?php

namespace App\Observers;

use App\Jobs\GenerateImageJob;
use App\Jobs\GenerateImagePromptJob;
use App\Jobs\StoreImageJob;
use App\Models\Image;
use App\Services\StorageService;
use Illuminate\Support\Facades\Bus;

class ImageObserver
{
    /**
     * Handle the Image "created" event.
     */
    public function created(Image $image): void
    {
            Bus::chain([
                new GenerateImagePromptJob($image),
                new GenerateImageJob($image),
                new StoreImageJob($image)
            ])->dispatch();
    }

    /**
     * Handle the Image "updated" event.
     */
    public function updated(Image $image): void
    {
        //
    }

    /**
     * Handle the Image "deleted" event.
     */
    public function deleted(Image $image): void
    {
        $path = ltrim(parse_url($image->image_url, PHP_URL_PATH), '/');
        StorageService::deleteFile($path);
    }



    /**
     * Handle the Image "restored" event.
     */
    public function restored(Image $image): void
    {
        //
    }

    /**
     * Handle the Image "force deleted" event.
     */
    public function forceDeleted(Image $image): void
    {

    }
}
