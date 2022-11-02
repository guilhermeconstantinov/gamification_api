<?php

namespace App\Services\User;

use Illuminate\Support\Collection;

interface UserServiceInterface
{
    /**
     * @return Collection
     */
    public function user();

    public function logout();

    public function create($request);

    public function checkin();

    public function simulation();

    public function generateNumber();

    public function validationCode($request);

    public function readValidation($request);

}
