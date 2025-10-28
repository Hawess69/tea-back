<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\MenPost;
use App\Services\AlertService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * Process alert job for name matching
 * 
 * Handles background processing of alert matches
 * when new men posts are created.
 * 
 * @package App\Jobs
 */
final class ProcessAlertJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     * 
     * @param MenPost $menPost
     */
    public function __construct(
        private readonly MenPost $menPost
    ) {}

    /**
     * Execute the job.
     * 
     * @param AlertService $alertService
     * @return void
     */
    public function handle(AlertService $alertService): void
    {
        try {
            Log::info('Processing alert matches for men post', [
                'post_id' => $this->menPost->id,
                'full_name' => $this->menPost->full_name,
            ]);

            $alertService->processAlertMatches($this->menPost);

            Log::info('Alert processing completed', [
                'post_id' => $this->menPost->id,
            ]);
        } catch (\Exception $e) {
            Log::error('Alert processing failed', [
                'post_id' => $this->menPost->id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Handle a job failure.
     * 
     * @param \Throwable $exception
     * @return void
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('ProcessAlertJob failed', [
            'post_id' => $this->menPost->id,
            'error' => $exception->getMessage(),
        ]);
    }
}


