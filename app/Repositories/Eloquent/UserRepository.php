<?php

namespace App\Repositories\Eloquent;

use App\Models\AccessCode;
use App\Models\LuckyNumber;
use App\Models\Raffle;
use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class UserRepository implements UserRepositoryInterface
{
    public function user()
    {
        return User::select('name', 'email', 'birthdate', 'phone', 'status')
            ->with(['accessCodes' => function($query) {
                $query->orderBy('id');
            }, 'luckyNumber'])
            ->find(Auth::id());
    }

    public function create($request)
    {
        if(!$request['email'] || $request['email'] == ''){
            $request['email'] = 'Email não informado';
        }
        $request['password'] =  Hash::make($request['password']);
        $user = User::create($request);

        if($user){
            return $user;
        }

        return false;
    }

    public function logout()
    {
        $user = Auth::user();
        $logout = $user->tokens()->delete();

        if($logout){
            return response()->json([
                'message' => 'Deslogado com sucesso'
            ],200);
        }

        return response()->json([
            'message' => 'Problema ao deslogar'
        ],400);
    }

    public function generateAccessCode($status, $userId)
    {
        $user = User::where('id', $userId)->first();

        $accessCode = AccessCode::where('user_id', $user->id)
            ->where('status', $status)
            ->first();

        if($accessCode){
            return $accessCode;
        }

        $accessCode = AccessCode::create([
            'user_id' => $user->id,
            'status' => $status,
            'code' => Str::uuid()
        ]);

        $image = $this->qrcodeGenerate($accessCode->code);
        return $this->uploadImage($accessCode->code, $image);
    }

    public function generateLuckyNumber($userId)
    {

        if($this->userHasNumber($userId)){
            return false;
        }

        $randomNumber = $this->randomNumber();

        return LuckyNumber::create([
            'number' => $randomNumber,
            'user_id' => $userId,
            'date' => Carbon::now()
        ]);
    }

    public function randomNumber()
    {
        $number = strval(mt_rand(100000, 999999));

        while($this->existNumber($number)){
            $number = mt_rand(100000, 999999);
        }

        return $number;
    }

    public function userHasNumber($userId)
    {
        return LuckyNumber::where('user_id', $userId)->exists();
    }

    public function existNumber($number)
    {
        return LuckyNumber::where('number', $number)->exists();
    }

    public function totalLuckyNumbers()
    {
        return LuckyNumber::where('drawn', false)
            ->get()
            ->count();
    }

    public function getLastRaffle()
    {
        return Raffle::latest()->first();
    }

    public function show($id)
    {
        return User::find($id);
    }

    public function qrcodeGenerate($accessCode)
    {
        return QrCode::size(928)->margin(2)->format('png')->generate($accessCode);
    }

    public function uploadImage($fileName, $image)
    {
        $fileName = $fileName.'.png';
        Storage::disk('public')->put($fileName, $image);

        $url = config('env.APP_URL').'/api/images/'.$fileName;

        if(config('env.APP_DEVELOP') == 'true'){
            $url = config('env.NGROK').'/api/images/'.$fileName;
        }

        return $url;
    }

}
