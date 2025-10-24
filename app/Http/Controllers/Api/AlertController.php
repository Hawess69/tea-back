<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Alert\StoreAlertRequest;
use App\Http\Resources\AlertResource;
use App\Services\AlertService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Alert controller for name tracking and notifications
 * 
 * Handles alert creation, management, and monitoring
 * for men post name matching and notifications.
 * 
 * @package App\Http\Controllers\Api
 */
final class AlertController extends Controller
{
    public function __construct(
        private readonly AlertService $alertService
    ) {}

    /**
     * Display a listing of user alerts
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $alerts = $this->alertService->getUserAlerts($request->user());

            return response()->json([
                'alerts' => AlertResource::collection($alerts),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve alerts',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created alert
     * 
     * @param StoreAlertRequest $request
     * @return JsonResponse
     */
    public function store(StoreAlertRequest $request): JsonResponse
    {
        try {
            $alert = $this->alertService->createAlert($request->user(), $request->validated());

            return response()->json([
                'message' => 'Alert created successfully',
                'alert' => new AlertResource($alert),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create alert',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified alert
     * 
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        try {
            $deleted = $this->alertService->deleteAlert($request->user(), $id);

            if (!$deleted) {
                return response()->json([
                    'message' => 'Alert not found',
                ], 404);
            }

            return response()->json([
                'message' => 'Alert deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete alert',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Toggle alert status
     * 
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function toggle(Request $request, int $id): JsonResponse
    {
        try {
            $alert = $this->alertService->toggleAlert($request->user(), $id);

            return response()->json([
                'message' => 'Alert status updated successfully',
                'alert' => new AlertResource($alert),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to toggle alert',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}