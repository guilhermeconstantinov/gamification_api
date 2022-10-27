<?php

namespace App\Repositories\Interfaces;

interface UserRepositoryInterface
{
    public function logout();

    public function create($request);

    public function user();

    public function generateAccessCode($status, $userId);

    public function generateLuckyNumber($userId);

    public function randomNumber();

    public function existNumber($number);

    public function getLastRaffle();

    public function show($id);

}
