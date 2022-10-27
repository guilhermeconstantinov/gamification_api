<?php

namespace App\Services\BackOffice;

use App\Jobs\SendWhatsappJob;
use App\Repositories\Interfaces\BackOfficeRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Interfaces\WhatsappRepositoryInterface;
use App\StatusType;

class BackOfficeService implements BackOfficeServiceInterface
{
    protected $userRepository;
    protected $whatsappRepository;
    protected $backOfficeRepository;

    public function __construct(UserRepositoryInterface $userRepository, WhatsappRepositoryInterface $whatsappRepository, BackOfficeRepositoryInterface $backOfficeRepository)
    {
        $this->backOfficeRepository = $backOfficeRepository;
        $this->userRepository = $userRepository;
        $this->whatsappRepository = $whatsappRepository;
    }

    public function qrcodeRead($request)
    {
        $data = $request->validated();
        return  $this->backOfficeRepository->qrcodeRead($data['qrcode']);
    }

    public function wonPrize($request)
    {
        $data = $request->validated();

        $numbers = $this->backOfficeRepository->getRandomLuckyNumber($data['quantity']);

        if($data['quantity'] == 0){
            return response()->json(['message' => 'Somente valores maiores que zero'], 400);

        }

        if($numbers->count() == 0){
            return response()->json(['message' => 'Acabou os números, aguarde novos registros'], 400);
        }

        if($numbers->count() < $data['quantity']){
            return response()->json(['message' => 'A quantidade de números para sorteio é menor do que o esperado'], 400);
        }

        $raffle = $this->backOfficeRepository->createRaffle();

        foreach($numbers as $number) {
            $number->drawn = true;
            $number->raffle_id = $raffle->id;
            $number->save();

            $user = $this->userRepository->show($number->user_id);
            $qrcodeLink = $this->userRepository->generateAccessCode(StatusType::YOU_WON, $user->id);
            $user->status = StatusType::YOU_WON;
            $user->save();
            SendWhatsappJob::dispatch('raffle', $user, $qrcodeLink, $number->number);
        }

        return response()->json(['raffle' => $raffle]);
    }

    public function rafflesList()
    {
        $raffles = $this->backOfficeRepository->rafflesList();
        return response()->json(['raffles' => $raffles],200);
    }

    public function getNumbersByRaffleId($request)
    {
        $data = $request->validated();

        $luckyNumbers = $this->backOfficeRepository->getNumbersByRaffleId($data['raffle_number']);

        if($luckyNumbers == false){
            return response()->json(
                [
                    'message' => 'Nenhum número encontrado para esse sorteio'
                ], 400);
        }
        return response()->json($luckyNumbers);
    }

    public function luckyNumberCount()
    {
        $total = $this->backOfficeRepository->totalLuckyNumbers();

        return response()->json([
            'total' => $total
        ]);
    }

    public function consultUsers($request)
    {
        $data = $request->validated();
        $data['phone'] = preg_replace("/([-\(\)\s])/", "", $data['phone']);
        return $this->backOfficeRepository->consultUser($data['phone']);
    }

    public function notifyRaffle()
    {
        $users = $this->backOfficeRepository->usersForRaffle();

        if($users->count() == 0){
            return response()->json(['message' => 'Nenhum usuário encontrado para notificar'], 400);
        }

        foreach ($users as $user){
            $userForId = $this->backOfficeRepository->show($user->id);
            SendWhatsappJob::dispatch('notifyRaffle', $userForId);
        }

        return response()->json(['message' => 'Notificação de aviso enviado com sucesso!'], 200);
    }
}
