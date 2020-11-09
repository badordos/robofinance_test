<?php

namespace App\Jobs;

use App\Http\Repositories\RemittanceRepo;
use App\Http\Services\RemittanceService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DoRemittanceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $remittanceId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $remittanceId)
    {
        $this->remittanceId = $remittanceId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $repo = app(RemittanceRepo::class);
        $service = app(RemittanceService::class);

        $remittance = $repo->getById($this->remittanceId);

        if ($service->checkDoRemittance($remittance)) {
            $service->doRemittance($remittance);
        }
    }
}
