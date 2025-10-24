<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Feed post resource for API responses
 * 
 * Transforms feed post data for consistent API responses
 * with voting information and relationships.
 * 
 * @package App\Http\Resources
 */
final class FeedPostResource extends JsonResource
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
            'image_url' => $this->image_url,
            'upvotes' => $this->upvotes,
            'downvotes' => $this->downvotes,
            'comments_count' => $this->comments_count,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
            
            // Relationships (only when loaded)
            'user' => $this->whenLoaded('user', function () {
                return new UserResource($this->user);
            }),
            'votes' => $this->whenLoaded('votes', function () {
                return VoteResource::collection($this->votes);
            }),
            'comments' => $this->whenLoaded('comments', function () {
                return CommentResource::collection($this->comments);
            }),
            
            // Computed fields
            'score' => $this->upvotes - $this->downvotes,
            'trending_score' => $this->when(isset($this->trending_score), $this->trending_score),
        ];
    }
}


