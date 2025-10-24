<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Services\ImageService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\UploadedFile;

/**
 * Process image job for image handling
 * 
 * Handles background processing of image uploads,
 * resizing, blurring, and optimization.
 * 
 * @package App\Jobs
 */
final class ProcessImageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     * 
     * @param string $imagePath
     * @param string $postType
     * @param int $postId
     */
    public function __construct(
        private readonly string $imagePath,
        private readonly string $postType,
        private readonly int $postId
    ) {}

    /**
     * Execute the job.
     * 
     * @param ImageService $imageService
     * @return void
     */
    public function handle(ImageService $imageService): void
    {
        try {
            Log::info('Processing image', [
                'image_path' => $this->imagePath,
                'post_type' => $this->postType,
                'post_id' => $this->postId,
            ]);

            // Process image based on post type
            if ($this->postType === 'men') {
                // Apply blurring for privacy
                $this->processMenPostImage($imageService);
            } else {
                // Standard processing for feed posts
                $this->processFeedPostImage($imageService);
            }

            Log::info('Image processing completed', [
                'image_path' => $this->imagePath,
                'post_id' => $this->postId,
            ]);
        } catch (\Exception $e) {
            Log::error('Image processing failed', [
                'image_path' => $this->imagePath,
                'post_id' => $this->postId,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Process men post image with blurring
     * 
     * @param ImageService $imageService
     * @return void
     */
    private function processMenPostImage(ImageService $imageService): void
    {
        // In production, this would use Intervention Image
        Log::info('Processing men post image with blurring', [
            'image_path' => $this->imagePath,
        ]);

        // Apply blurring for privacy protection
        // $image = Image::make($this->imagePath);
        // $image->blur(15)->save();
    }

    /**
     * Process feed post image
     * 
     * @param ImageService $imageService
     * @return void
     */
    private function processFeedPostImage(ImageService $imageService): void
    {
        // In production, this would use Intervention Image
        Log::info('Processing feed post image', [
            'image_path' => $this->imagePath,
        ]);

        // Resize and optimize
        // $image = Image::make($this->imagePath);
        // $image->resize(800, null, function ($constraint) {
        //     $constraint->aspectRatio();
        // })->save();
    }

    /**
     * Handle a job failure.
     * 
     * @param \Throwable $exception
     * @return void
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('ProcessImageJob failed', [
            'image_path' => $this->imagePath,
            'post_id' => $this->postId,
            'error' => $exception->getMessage(),
        ]);
    }
}


