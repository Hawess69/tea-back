<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Event resource for API responses
 * 
 * Transforms event data for consistent API responses
 * with creator information and relationships.
 * 
 * @package App\Http\Resources
 */
final class EventResource extends JsonResource
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
            'description' => $this->description,
            'location' => $this->location,
            'event_date' => $this->event_date?->toISOString(),
            'image' => $this->image,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
            
            // Relationships (only when loaded)
            'creator' => $this->whenLoaded('creator', function () {
                return new UserResource($this->creator);
            }),
            
            // Computed fields
            'is_upcoming' => $this->event_date > now(),
            'days_until' => $this->when(isset($this->event_date), function () {
                return $this->event_date->diffInDays(now());
            }),
        ];
    }
}


