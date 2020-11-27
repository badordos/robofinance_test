<?php

namespace App\Http\Services;

use App\Http\Dto\RemittanceDto;
use App\Http\Repositories\RemittanceRepo;
use App\Http\Repositories\UsersRepo;
use App\Http\Requests\RemittanceStoreRequest;
use App\Jobs\DoRemittanceJob;
use App\Models\Interfaces\IRemittance;
use App\Models\Remittance;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RemittanceService extends AbstractService
{
    /**
     * @var array
     */
    private $data = [];

    /**
     * @var
     */
    private $remittanceRepo;


    private $usersRepo;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->usersRepo = app(UsersRepo::class);
        $this->remittanceRepo = app(remittanceRepo::class);
    }

    /**
     * Данные необходимые для главной страницы
     *
     * @return array
     */
    public function getIndexData()
    {
        $this->data['user'] = auth()->user() ?? $this->usersRepo->getFirst();
        $this->data['users'] = $this->usersRepo->getAll();
        $this->data['now'] = Carbon::now()->addHour()->startOfHour();

        return $this->data;
    }

    /**
     * Запись нового запланированного перевода и отравка его в очередь
     *
     * @param RemittanceDto $dto
     * @return void
     */
    public function store(RemittanceDto $dto)
    {
        $remittance = Remittance::create($dto->toArray());
        if ($this->checkDoRemittance($remittance)) {
            $job = (new DoRemittanceJob($remittance->getId()));
            //Для локального окружения выполняем все джобы через 10 секунд
            if (env('APP_ENV') === 'local') {
                $job->delay(Carbon::now()->addSeconds(10));
            } else {
                $job->delay(Carbon::parse($remittance->getDoAt()));
            }

            dispatch($job);
        }
    }

    /**
     * Проверяем, можно ли совершать перевод
     *
     * @param IRemittance $remittance
     * @return bool
     */
    public function checkDoRemittance(IRemittance $remittance)
    {
        $value = $this->remittanceRepo->getNotDoneRemittancesByUser($remittance->getPayerId()) + $remittance->getValue();
        if ($remittance->payer->balance < $value) {
            $remittance->setStatus(Remittance::STATUS_FAILED);
            $remittance->save();
            throw new \Exception('Перевод ' . $remittance->getId() . ' уводит баланс ниже нуля');
        }
        return true;
    }

    /**
     * @param IRemittance $remittance
     * @return bool
     */
    public function doRemittance(IRemittance $remittance)
    {
        //TODO DB::transaction
        $payer = $remittance->payer;
        $recipient = $remittance->recipient;

        $payer->balance -= $remittance->value;
        $recipient->balance += $remittance->value;

        $remittance->setStatus(Remittance::STATUS_DONE);

        return $payer->save() && $recipient->save() && $remittance->save();
    }

}
