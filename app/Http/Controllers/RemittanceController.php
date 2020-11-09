<?php

namespace App\Http\Controllers;

use App\Http\Dto\RemittanceDto;
use App\Http\Requests\RemittanceStoreRequest;
use App\Http\Services\RemittanceService;

class RemittanceController extends Controller
{
    /**
     * @var \Illuminate\Contracts\Foundation\Application|mixed
     */
    private $remittanceService;

    /**
     * RemittanceController constructor.
     */
    public function __construct()
    {
        $this->remittanceService = app(RemittanceService::class);
    }

    /**
     * @param RemittanceStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(RemittanceStoreRequest $request)
    {
        $dto = RemittanceDto::createFromRequest($request);
        $this->remittanceService->store($dto);
        return back()->with('message', 'Перевод запланирован');
    }
}
