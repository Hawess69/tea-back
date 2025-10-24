<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\User;
use App\Services\AuthService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * Auth service unit tests
 * 
 * Tests authentication service business logic
 * including registration, login, and profile management.
 * 
 * @package Tests\Unit
 */
final class AuthServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private AuthService $authService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->authService = new AuthService();
    }

    /**
     * Test user registration
     */
    public function test_can_register_user(): void
    {
        $userData = [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'password' => 'Password123!',
        ];

        $user = $this->authService->register($userData);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($userData['name'], $user->name);
        $this->assertEquals($userData['email'], $user->email);
        $this->assertEquals($userData['phone'], $user->phone);
        $this->assertTrue(Hash::check($userData['password'], $user->password));
        $this->assertEquals('user', $user->role);
        $this->assertEquals('active', $user->status);
    }

    /**
     * Test user login with valid credentials
     */
    public function test_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $credentials = [
            'email' => 'test@example.com',
            'password' => 'password123',
        ];

        $result = $this->authService->login($credentials);

        $this->assertArrayHasKey('user', $result);
        $this->assertArrayHasKey('token', $result);
        $this->assertEquals($user->id, $result['user']->id);
        $this->assertNotEmpty($result['token']);
    }

    /**
     * Test user login with invalid credentials
     */
    public function test_login_fails_with_invalid_credentials(): void
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $credentials = [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ];

        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $this->authService->login($credentials);
    }

    /**
     * Test user logout
     */
    public function test_can_logout_user(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token');

        $this->authService->logout($user);

        $this->assertDatabaseMissing('personal_access_tokens', [
            'id' => $token->accessToken->id,
        ]);
    }

    /**
     * Test get user profile
     */
    public function test_can_get_user_profile(): void
    {
        $user = User::factory()->create();

        $profile = $this->authService->getProfile($user);

        $this->assertEquals($user->id, $profile->id);
        $this->assertTrue($profile->relationLoaded('alerts'));
        $this->assertTrue($profile->relationLoaded('notifications'));
    }

    /**
     * Test update user profile
     */
    public function test_can_update_user_profile(): void
    {
        $user = User::factory()->create();

        $updateData = [
            'name' => 'Updated Name',
            'phone' => '+1234567890',
        ];

        $updatedUser = $this->authService->updateProfile($user, $updateData);

        $this->assertEquals('Updated Name', $updatedUser->name);
        $this->assertEquals('+1234567890', $updatedUser->phone);
    }

    /**
     * Test get user notifications
     */
    public function test_can_get_user_notifications(): void
    {
        $user = User::factory()->create();

        $notifications = $this->authService->getNotifications($user);

        $this->assertInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class, $notifications);
    }
}


