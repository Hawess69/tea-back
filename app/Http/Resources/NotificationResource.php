<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Notification resource for API responses
 * 
 * Transforms notification data for consistent API responses
 * with user information and status.
 * 
 * @package App\Http\Resources
 */
final class NotificationResource extends JsonResource
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
            'title' => $this->title,
            'body' => $this->body,
            'type' => $this->type,
            'sent_via' => $this->sent_via,
            'read_at' => $this->read_at?->toISOString(),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
            
            // Relationships (only when loaded)
            'user' => $this->whenLoaded('user', function () {
                return new UserResource($this->user);
            }),
            
            // Computed fields
            'is_read' => !is_null($this->read_at),
            'time_ago' => $this->created_at?->diffForHumans(),
        ];
    }
}


