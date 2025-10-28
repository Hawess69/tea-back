<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\UploadedFile;

/**
 * Image service for processing and managing images
 * 
 * Handles image uploads, resizing, blurring, and storage
 * for user avatars, post images, and event photos.
 * 
 * @package App\Services
 */
final class ImageService
{
    /**
     * Upload and process user avatar
     * 
     * @param UploadedFile $file
     * @param int $userId
     * @return string
     */
    public function uploadAvatar(UploadedFile $file, int $userId): string
    {
        $filename = 'avatars/' . $userId . '_' . time() . '.' . $file->getClientOriginalExtension();
        
        // Store the file
        $path = $file->storeAs('public', $filename);
        
        // Process image (resize, optimize)
        $this->processAvatar($path);
        
        return Storage::disk('public')->url($filename);
    }

    /**
     * Upload and process post image
     * 
     * @param UploadedFile $file
     * @param int $postId
     * @param string $postType
     * @return string
     */
    public function uploadPostImage(UploadedFile $file, int $postId, string $postType): string
    {
        $filename = "posts/{$postType}/{$postId}_" . time() . '.' . $file->getClientOriginalExtension();
        
        // Store the file
        $path = $file->storeAs('public', $filename);
        
        // Process image based on post type
        if ($postType === 'men') {
            $this->processMenPostImage($path);
        } else {
            $this->processFeedPostImage($path);
        }
        
        return Storage::disk('public')->url($filename);
    }

    /**
     * Upload and process event image
     * 
     * @param UploadedFile $file
     * @param int $eventId
     * @return string
     */
    public function uploadEventImage(UploadedFile $file, int $eventId): string
    {
        $filename = 'events/' . $eventId . '_' . time() . '.' . $file->getClientOriginalExtension();
        
        // Store the file
        $path = $file->storeAs('public', $filename);
        
        // Process image (resize, optimize)
        $this->processEventImage($path);
        
        return Storage::disk('public')->url($filename);
    }

    /**
     * Process avatar image
     * 
     * @param string $path
     * @return void
     */
    private function processAvatar(string $path): void
    {
        // In production, this would use Intervention Image
        Log::info('Avatar processing requested', ['path' => $path]);
        
        // Resize to 200x200, optimize, create thumbnail
        // $image = Image::make($path);
        // $image->resize(200, 200)->save();
    }

    /**
     * Process feed post image
     * 
     * @param string $path
     * @return void
     */
    private function processFeedPostImage(string $path): void
    {
        // In production, this would use Intervention Image
        Log::info('Feed post image processing requested', ['path' => $path]);
        
        // Resize to max 800px width, optimize
        // $image = Image::make($path);
        // $image->resize(800, null, function ($constraint) {
        //     $constraint->aspectRatio();
        // })->save();
    }

    /**
     * Process men post image with blurring
     * 
     * @param string $path
     * @return void
     */
    private function processMenPostImage(string $path): void
    {
        // In production, this would use Intervention Image
        Log::info('Men post image processing requested', ['path' => $path]);
        
        // Resize and blur for privacy
        // $image = Image::make($path);
        // $image->resize(600, null, function ($constraint) {
        //     $constraint->aspectRatio();
        // })->blur(15)->save();
    }

    /**
     * Process event image
     * 
     * @param string $path
     * @return void
     */
    private function processEventImage(string $path): void
    {
        // In production, this would use Intervention Image
        Log::info('Event image processing requested', ['path' => $path]);
        
        // Resize to max 1200px width, optimize
        // $image = Image::make($path);
        // $image->resize(1200, null, function ($constraint) {
        //     $constraint->aspectRatio();
        // })->save();
    }

    /**
     * Delete image from storage
     * 
     * @param string $url
     * @return bool
     */
    public function deleteImage(string $url): bool
    {
        $filename = $this->getFilenameFromUrl($url);
        
        if ($filename) {
            return Storage::delete('public/' . $filename);
        }
        
        return false;
    }

    /**
     * Get filename from URL
     * 
     * @param string $url
     * @return string|null
     */
    private function getFilenameFromUrl(string $url): ?string
    {
        $path = parse_url($url, PHP_URL_PATH);
        return basename($path);
    }

    /**
     * Validate image file
     * 
     * @param UploadedFile $file
     * @return bool
     */
    public function validateImage(UploadedFile $file): bool
    {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $maxSize = 5 * 1024 * 1024; // 5MB
        
        return in_array($file->getMimeType(), $allowedTypes) && 
               $file->getSize() <= $maxSize;
    }

    /**
     * Get image dimensions
     * 
     * @param string $path
     * @return array
     */
    public function getImageDimensions(string $path): array
    {
        // In production, this would use getimagesize() or Intervention Image
        return [
            'width' => 0,
            'height' => 0,
        ];
    }

    /**
     * Generate image thumbnail
     * 
     * @param string $path
     * @param int $width
     * @param int $height
     * @return string
     */
    public function generateThumbnail(string $path, int $width = 150, int $height = 150): string
    {
        // In production, this would use Intervention Image
        Log::info('Thumbnail generation requested', [
            'path' => $path,
            'width' => $width,
            'height' => $height,
        ]);
        
        return $path; // Return original path for now
    }
}


