<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Models\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Event controller for event management
 * 
 * Handles event listing and retrieval for
 * community events and gatherings.
 * 
 * @package App\Http\Controllers\Api
 */
final class EventController extends Controller
{
    /**
     * Display a listing of events
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $page = (int) $request->get('page', 1);
            $perPage = (int) $request->get('per_page', 20);
            $upcoming = $request->get('upcoming', true);

            $query = Event::with('creator');

            if ($upcoming) {
                $query->where('event_date', '>', now());
            }

            $events = $query->orderBy('event_date', 'asc')
                ->paginate($perPage, ['*'], 'page', $page);

            return response()->json([
                'events' => EventResource::collection($events),
                'pagination' => [
                    'current_page' => $events->currentPage(),
                    'last_page' => $events->lastPage(),
                    'per_page' => $events->perPage(),
                    'total' => $events->total(),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve events',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified event
     * 
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $event = Event::with('creator')->find($id);

            if (!$event) {
                return response()->json([
                    'message' => 'Event not found',
                ], 404);
            }

            return response()->json([
                'event' => new EventResource($event),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve event',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}