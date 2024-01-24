<?php

use App\Enums\ImageStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->string('keyword');
            $table->text('prompt')->nullable();
            // $table->string('image_path')->nullable();
            $table->string('image_url')->nullable();
            $table->text('remote_url')->nullable();
            $table->integer('progress_percentage')->default(0);
            $table->text('failure_details')->nullable();
            $table->enum('status', ImageStatusEnum::getValues())->default(ImageStatusEnum::NEW->value);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};
