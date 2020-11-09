<?php

namespace App\Http\Repositories;

use App\Models\Remittance;

class RemittanceRepo
{

    /**
     * Получим суммарное значение переводов которые уже запланированы и не исполнены
     *
     * @param int $userId
     * @return mixed
     */
    public function getNotDoneRemittancesByUser(int $userId)
    {
        return Remittance::where('payer_id', $userId)
            ->where('done', 0)
            ->sum('value');
    }

    public function getById(int $id)
    {
        return Remittance::findOrFail($id);
    }
}
