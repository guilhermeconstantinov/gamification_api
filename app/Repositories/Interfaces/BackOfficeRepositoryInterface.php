<?php

namespace App\Repositories\Interfaces;

interface BackOfficeRepositoryInterface
{
    public function totalLuckyNumbers();

    public function consultUser($phone);

    public function getNumbersByRaffleId($raffleNumber);

    public function rafflesList();

    public function getRandomLuckyNumber($quantity);

    public function createRaffle();

    public function getQrcode($qrcode);

    public function getUserWithId ($id);

    public function qrcodeRead($qrcode);

    public function usersForRaffle();

    public function show($id);

}
