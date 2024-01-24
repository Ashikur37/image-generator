<?php


use App\Services\ImageService;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Response;

it('throws an exception when the API returns an error', function () {
    Http::fake([
        '*' => Http::response(
            [
                "error" => [
                    "code" => 400,
                    "message" => "Invalid prompt: prompt must be a string.",
                    "type" => "invalid_request_error"
                ]
            ], Response::HTTP_BAD_REQUEST),
    ]);
    ImageService::generate('sample prompt');
})->throws(Exception::class, "Invalid prompt: prompt must be a string.");
