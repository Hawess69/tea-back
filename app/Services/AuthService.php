<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\ValidationException;

/**
 * Authentication service for user management
 * 
 * Handles user registration, login, profile management,
 * and token-based authentication using Laravel Sanctum.
 * 
 * @package App\Services
 */
final class AuthService
{
    /**
     * Register a new user
     * 
     * @param array $data User registration data
     * @return User
     * @throws ValidationException
     */
    public function register(array $data): User
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'password' => Hash::make($data['password']),
            'role' => 'user',
            'status' => 'active',
        ]);

        // Fire registered event for email verification
        event(new Registered($user));

        return $user;
    }

    /**
     * Authenticate user and create token
     * 
     * @param array $credentials Login credentials
     * @return array
     * @throws ValidationException
     */
    public function login(array $credentials): array
    {
        if (!Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $user = Auth::user();
        $token = $user->createToken('auth-token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    /**
     * Logout user and revoke token
     * 
     * @param User $user
     * @return void
     */
    public function logout(User $user): void
    {
        $user->currentAccessToken()->delete();
    }

    /**
     * Get authenticated user profile
     * 
     * @param User $user
     * @return User
     */
    public function getProfile(User $user): User
    {
        return $user->load(['alerts', 'notifications']);
    }

    /**
     * Update user profile
     * 
     * @param User $user
     * @param array $data
     * @return User
     */
    public function updateProfile(User $user, array $data): User
    {
        $user->update($data);
        return $user->fresh();
    }

    /**
     * Get user notifications
     * 
     * @param User $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getNotifications(User $user)
    {
        return $user->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(20);
    }
}


