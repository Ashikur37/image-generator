<?php

namespace App\Models;

use App\Enums\ImageStatusEnum;
use App\Jobs\GenerateImageJob;
use App\Jobs\GenerateImagePromptJob;
use App\Jobs\StoreImageJob;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Bus;

class Image extends Model
{
    use  HasFactory;
    protected $fillable = ['keyword', 'prompt', 'status', 'progress_percentage', 'image_path', 'image_url', 'remote_url'];

    protected $casts = [
        'status' => ImageStatusEnum::class,
    ];

    public function updatePrompt(string $prompt): bool
    {
        return $this->update([
            'prompt' => trim($prompt),
            'status' => ImageStatusEnum::PROCESSING,
            'progress_percentage' => 25,
        ]);
    }
    public function updateRemoteUrl(string $url): bool
    {
        return $this->update([
            'remote_url' => $url,
            'progress_percentage' => 50,
        ]);
    }
    public function updateImageUrl(string $url): bool
    {
        return $this->update([
            'status' => ImageStatusEnum::COMPLETED,
            'image_url' => $url,
            'progress_percentage' => 100,
        ]);
    }
    public function retry(): void
    {
        if ($this->status === ImageStatusEnum::FAILED) {
            $jobs = [];
            if ($this->progress_percentage == 0) {
                $jobs[] = new GenerateImagePromptJob($this);
            }
            if ($this->progress_percentage <= 25) {
                $jobs[] = new GenerateImageJob($this);
            }
            if ($this->progress_percentage <= 50) {
                $jobs[] = new StoreImageJob($this);
            }
            if (!empty($jobs)) {
                Bus::chain($jobs)->dispatch();
            }
        }
    }
    public function fail($message): void
    {
        Log::emergency("Image image->generation failed for: " . $message);
        $this->status = ImageStatusEnum::FAILED->value;
        $this->failure_details = $message;
        $this->save();
    }
}
