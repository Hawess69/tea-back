<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Men post resource for API responses
 * 
 * Transforms men post data for consistent API responses
 * with flagging information and relationships.
 * 
 * @package App\Http\Resources
 */
final class MenPostResource extends JsonResource
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
            'full_name' => $this->full_name,
            'city' => $this->city,
            'tags' => $this->tags,
            'caption' => $this->caption,
            'photo_url' => $this->photo_url,
            'flag_counts' => $this->flag_counts,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
            
            // Relationships (only when loaded)
            'user' => $this->whenLoaded('user', function () {
                return new UserResource($this->user);
            }),
            'flags' => $this->whenLoaded('flags', function () {
                return FlagResource::collection($this->flags);
            }),
            'comments' => $this->whenLoaded('comments', function () {
                return CommentResource::collection($this->comments);
            }),
            
            // Computed fields
            'total_flags' => array_sum($this->flag_counts ?? []),
            'flag_ratio' => $this->when(isset($this->flag_counts), function () {
                $counts = $this->flag_counts ?? [];
                $total = array_sum($counts);
                return $total > 0 ? [
                    'red_ratio' => ($counts['red'] ?? 0) / $total,
                    'green_ratio' => ($counts['green'] ?? 0) / $total,
                    'neutral_ratio' => ($counts['neutral'] ?? 0) / $total,
                ] : null;
            }),
        ];
    }
}


