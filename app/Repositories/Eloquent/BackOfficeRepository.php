<?php

namespace App\Repositories\Eloquent;

use App\Models\AccessCode;
use App\Models\LuckyNumber;
use App\Models\Raffle;
use App\Models\User;
use App\Repositories\Interfaces\BackOfficeRepositoryInterface;
use App\StatusType;
use Carbon\Carbon;

class BackOfficeRepository implements BackOfficeRepositoryInterface
{
    public function totalLuckyNumbers()
    {
        return LuckyNumber::where('drawn', false)
            ->get()
            ->count();
    }

    public function usersForRaffle()
    {
        return User::select('users.id as id', 'users.phone as phone', 'users.name as name')
            ->join('lucky_numbers', 'users.id', '=', 'lucky_numbers.user_id')
            ->where('lucky_numbers.drawn', false)
            ->get();
    }

    public function consultUser($document)
    {

        $user = User::select('id', 'name', 'document_cpf', 'phone', 'email', 'status')
            ->where('document_cpf', $document)
            ->first();

        if(!$user){
            return response()->json(['message' => 'Nenhum usuário encontrado para esse CPF'], 404);
        }

        $user = $user->toArray();

        $luckyNumber = LuckyNumber::where('user_id', $user['id'])->first();
        $user['lucky_number'] = null;
        if($luckyNumber){
            $user['lucky_number'] = $luckyNumber->number;
        }

        $accessCode = AccessCode::where('user_id', $user['id'])->get();

        $user['checkin_prize'] = false;
        $user['simulation_prize'] = false;
        $user['luckynumber_prize'] = false;

        foreach ($accessCode as $chave => $object){
            if($object->status == 'checkin' && $object->revoked){
                $user['checkin_prize'] = $object->revoked;
            }

            if($object->status == 'simulation' && $object->revoked){
                $user['simulation_prize'] = $object->revoked;
            }

            if($object->status == 'you_won' && $object->revoked){
                $user['luckynumber_prize'] = $object->revoked;
            }
        }

        return $user;

    }

    public function getNumbersByRaffleId($raffleNumber)
    {
        $raffle = Raffle::find($raffleNumber);
        $luckyNumbers = LuckyNumber::select('lucky_numbers.id', 'number', 'name')
            ->where('raffle_id', $raffleNumber)
            ->join('users', 'users.id', '=', 'lucky_numbers.user_id')
            ->orderBy('number')
            ->get();

        if($luckyNumbers->count() == 0){
            return false;
        }

        return ['luckyNumbers' => $luckyNumbers, 'raffle_date' => $raffle->date];
    }

    public function rafflesList()
    {
        return Raffle::select('id', 'date')->get();
    }

    public function getRandomLuckyNumber($quantity)
    {
        return LuckyNumber::where('drawn', false)
            ->whereHas('user', function($query) {
                return $query->where('status', '=', StatusType::SENT_NUMBER);
            })
            ->inRandomOrder()
            ->take($quantity)
            ->get();
    }

    public function createRaffle()
    {
        return Raffle::create(['date' => Carbon::now()]);
    }

    public function qrcodeRead($qrcode)
    {
        $accessCode = $this->getQrcode($qrcode);

        if($accessCode && $accessCode->revoked){
            return response()->json([
                'message' => 'QRCode já utilizado anteriormente',
            ],400);
        }

        if(!$accessCode){
            return response()->json([
                'message' => 'Nenhum QRCode encontrado no sistema',
            ],400);
        }

        $user = $this->getUserWithId($accessCode->user_id);

        if(!$user){
            return response()->json([
                'message' => 'Nenhum usuário vinculado a esse código de acesso',
            ],400);;
        }

        $accessCode->revoked = true;
        $accessCode->save();

        if($accessCode->status == StatusType::WAITING_CHECKIN)
        {
            $user->status = StatusType::CHECKIN;
            $user->save();
            return response()
                ->json([ 'message' => 'Primeira etapa concluída']);
        }

        if($accessCode->status == StatusType::WAITING_SIMULATION)
        {
            $user->status = StatusType::SIMULATION;
            $user->save();
            return response()
                ->json([ 'message' => 'Segunda etapa concluída']);
        }

        if($accessCode->status == StatusType::YOU_WON){
            $user->status = StatusType::WITHDREW_GIFT;
            $user->save();
            return response()
                ->json([ 'message' => 'Terceira etapa concluída']);
        }

        return response()->json(['message' => 'Nenhum status foi atualizado'], 400);

    }

    public function getUserWithId ($id)
    {
        return User::where('id', $id)->first();
    }

    public function show($id)
    {
        return User::find($id);
    }

    public function getQrcode($qrcode)
    {
        return AccessCode::where('code', $qrcode)->first();
    }

}
