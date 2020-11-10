<?php

namespace App\Http\Dto;

use App\Http\Requests\RemittanceStoreRequest;
use App\Models\Remittance;
use Carbon\Carbon;

class RemittanceDto extends AbstractDto
{
    public $payer_id;

    public $recipient_id;

    public $value;

    public $do_at;

    static function createFromRequest(RemittanceStoreRequest $request)
    {
        $dto = new self();
        $dto->payer_id = $request->payer_id;
        $dto->recipient_id = $request->recipient_id;
        $dto->value = $request->value;
        $dto->do_at =  Carbon::parse($request->do_at)->startOfHour()->format('Y-m-d H:i:s');
        $dto->status =  Remittance::STATUS_CREATED;

        return $dto;
    }
}
