<?php

namespace App\Services\BackOffice;

interface BackOfficeServiceInterface
{
    public function wonPrize($request);

    public function rafflesList();

    public function luckyNumberCount();

    public function consultUsers($request);

    public function getNumbersByRaffleId($request);

    public function qrcodeRead($request);

}
