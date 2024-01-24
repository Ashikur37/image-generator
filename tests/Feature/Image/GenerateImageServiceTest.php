<?php

use App\Services\ImageService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

it('returns a valid image URL from ImageService', function () {
    $url = "https://example.com/image.jpg";
    Http::fake([
        '*' => Http::response(
            [
                "created" => 1706019712,
                "data" => [  [ "url" => $url]]
            ], Response::HTTP_OK),
    ]);

    $imageUrl = ImageService::generate('sample prompt');
    expect($imageUrl)->toBeString()->toEqual($url);
});

