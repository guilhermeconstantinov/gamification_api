<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\CreateRequest;
use App\Http\Requests\User\LoginRequest;
use App\Services\User\UserServiceInterface;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    protected $userService;
    protected $whatsappService;

    public function __construct(userServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    public function login(LoginRequest $request)
    {
        return $this->userService->login($request);
    }

    public function user()
    {
        return $this->userService->user();
    }

    public function create(CreateRequest $request)
    {
        return $this->userService->create($request);
    }

    public function logout()
    {
        return $this->userService->logout();
    }

    public function checkin()
    {
        return $this->userService->checkin();
    }

    public function simulation()
    {
        return $this->userService->simulation();
    }

    public function generateNumber()
    {
        return $this->userService->generateNumber();
    }

    public function images($fileName){
        if(!Storage::disk('public')->exists($fileName)){
            response('',404);
        }

        $file = Storage::disk('public')->get($fileName);
        return response($file, 200, ['content-type' => 'image/png']);
    }

}
