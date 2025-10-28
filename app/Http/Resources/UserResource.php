<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * User resource for API responses
 * 
 * Transforms user data for consistent API responses
 * with proper data structure and relationships.
 * 
 * @package App\Http\Resources
 */
final class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     * 
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'avatar' => $this->avatar,
            'role' => $this->role,
            'status' => $this->status,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
            
            // Relationships (only when loaded)
            'alerts' => $this->whenLoaded('alerts', function () {
                return AlertResource::collection($this->alerts);
            }),
            'notifications' => $this->whenLoaded('notifications', function () {
                return NotificationResource::collection($this->notifications);
            }),
        ];
    }
}


