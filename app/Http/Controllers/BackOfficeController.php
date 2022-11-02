<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\ConsultUsersRequest;
use App\Http\Requests\User\QrcodeReadRequest;
use App\Http\Requests\User\RaffleLuckyNumberRequest;
use App\Http\Requests\User\WonPrizeRequest;
use App\Services\BackOffice\BackOfficeServiceInterface;
use Illuminate\Support\Facades\Mail;

class BackOfficeController extends Controller
{
    protected $backOfficeService;

    public function __construct(BackOfficeServiceInterface $backOfficeService)
    {
        $this->backOfficeService = $backOfficeService;
    }

    public function qrcodeRead(QrcodeReadRequest $request)
    {
        return $this->backOfficeService->qrcodeRead($request);
    }

    public function wonPrize(WonPrizeRequest $request)
    {
        return $this->backOfficeService->wonPrize($request);
    }

    public function luckyNumberCount()
    {
        return $this->backOfficeService->luckyNumberCount();
    }

    public function rafflesList()
    {
        return $this->backOfficeService->rafflesList();
    }

    public function consultUsers(ConsultUsersRequest $request)
    {
        return $this->backOfficeService->consultUsers($request);
    }

    public function getNumbersByRaffleId(RaffleLuckyNumberRequest $request)
    {
        return $this->backOfficeService->getNumbersByRaffleId($request);
    }
}
