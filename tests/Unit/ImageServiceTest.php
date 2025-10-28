<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Services\ImageService;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Image service unit tests
 * 
 * Tests image service functionality including
 * validation, upload, and processing operations.
 * 
 * @package Tests\Unit
 */
final class ImageServiceTest extends TestCase
{
    use RefreshDatabase;

    private ImageService $imageService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->imageService = new ImageService();
    }

    /**
     * Test image validation with valid file
     */
    public function test_can_validate_valid_image(): void
    {
        $file = UploadedFile::fake()->image('test.jpg', 800, 600);

        $isValid = $this->imageService->validateImage($file);

        $this->assertTrue($isValid);
    }

    /**
     * Test image validation with invalid file type
     */
    public function test_validation_fails_with_invalid_file_type(): void
    {
        $file = UploadedFile::fake()->create('test.txt', 1000, 'text/plain');

        $isValid = $this->imageService->validateImage($file);

        $this->assertFalse($isValid);
    }

    /**
     * Test image validation with oversized file
     */
    public function test_validation_fails_with_oversized_file(): void
    {
        $file = UploadedFile::fake()->image('test.jpg', 800, 600)->size(10240); // 10MB

        $isValid = $this->imageService->validateImage($file);

        $this->assertFalse($isValid);
    }

    /**
     * Test image validation with undersized dimensions
     */
    public function test_validation_fails_with_undersized_dimensions(): void
    {
        $file = UploadedFile::fake()->image('test.jpg', 50, 50);

        $isValid = $this->imageService->validateImage($file);

        $this->assertFalse($isValid);
    }

    /**
     * Test image validation with oversized dimensions
     */
    public function test_validation_fails_with_oversized_dimensions(): void
    {
        $file = UploadedFile::fake()->image('test.jpg', 5000, 5000);

        $isValid = $this->imageService->validateImage($file);

        $this->assertFalse($isValid);
    }

    /**
     * Test get image dimensions
     */
    public function test_can_get_image_dimensions(): void
    {
        $file = UploadedFile::fake()->image('test.jpg', 800, 600);

        $dimensions = $this->imageService->getImageDimensions($file);

        $this->assertEquals(800, $dimensions['width']);
        $this->assertEquals(600, $dimensions['height']);
    }

    /**
     * Test get file size
     */
    public function test_can_get_file_size(): void
    {
        $file = UploadedFile::fake()->image('test.jpg', 800, 600);

        $size = $this->imageService->getFileSize($file);

        $this->assertIsInt($size);
        $this->assertGreaterThan(0, $size);
    }

    /**
     * Test get file type
     */
    public function test_can_get_file_type(): void
    {
        $file = UploadedFile::fake()->image('test.jpg', 800, 600);

        $type = $this->imageService->getFileType($file);

        $this->assertIsString($type);
        $this->assertContains($type, ['image/jpeg', 'image/png', 'image/gif', 'image/webp']);
    }
}


