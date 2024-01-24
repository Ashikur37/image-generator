<?php

use App\Jobs\GenerateImageJob;
use App\Jobs\GenerateImagePromptJob;
use App\Jobs\StoreImageJob;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    //fake storage and queue
    // Arrange
    Bus::fake();
});


#check GenerateImagePromptJob job works
it('GenerateImagePromptJob works', function () { App\Models\Image::create(['keyword' => 'mountain','prompt'=>'nice mountain']);
    Bus::assertChained([
        GenerateImagePromptJob::class,
        GenerateImageJob::class,
        StoreImageJob::class,
    ]);
    $image = App\Models\Image::create(['keyword' => 'mountain','prompt'=>'nice mountain']);
    #GenerateImagePromptJob fake
    Http::fake([
        'https://api.openai.com/v1/chat/completions' => Http::response([
            "choices" => [
                [
                    'message' => [
                        'content' => 'This is a generated prompt'
                    ]
                ]
            ]
        ], Response::HTTP_OK),
    ]);
    $job = new GenerateImagePromptJob($image);
    $job->handle();

    expect($image->prompt)->toEqual('This is a generated prompt')
        ->and($image->progress_percentage)->toEqual(25);
});
