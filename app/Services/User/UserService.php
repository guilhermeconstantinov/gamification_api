<?php

namespace App\Services\User;

use App\Jobs\SendWhatsappJob;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Interfaces\WhatsappRepositoryInterface;
use App\StatusType;
use Illuminate\Support\Facades\Auth;
use Lcobucci\JWT\Exception;

class UserService implements UserServiceInterface
{

    protected $userRepository;
    protected $whatsappRepository;

    public function __construct(UserRepositoryInterface $userRepository, WhatsappRepositoryInterface $whatsappRepository)
    {
        $this->userRepository = $userRepository;
        $this->whatsappRepository = $whatsappRepository;
    }


    public function create($request)
    {
        $data = $request->validated();
        $user = $this->userRepository->create($data);

        if($user){
            return response()->json(["message" => 'Registrado com sucesso'], 200);
        }

        return response()->json(["message" => "Erro ao tentar registrar"], 400);
    }

    public function user()
    {
        return response()->json($this->userRepository->user());
    }

    public function logout()
    {
        return $this->userRepository->logout();
    }

    public function checkin()
    {
        $user = $this->userRepository->show(Auth::id());

        if($user->status == StatusType::WAITING_CHECKIN)
        {
            return response()->json(['message' => 'Você já iniciou este processo, por favor vá até o ponto indicado para retirar o seu brinde'], 400);
        }

        if($user->status >= StatusType::CHECKIN){
            return response()->json(['message' => 'Você já executou esse processo'], 400);
        }

        $qrcodeLink = $this->userRepository
            ->generateAccessCode(StatusType::WAITING_CHECKIN, $user->id);

        $user->status = StatusType::WAITING_CHECKIN;
        $user->save();
        SendWhatsappJob::dispatch('register', $user, $qrcodeLink);

        return response()->json(["message" => 'Processo de check-in foi iniciado'], 200);
    }

    public function simulation()
    {
        $user = $this->userRepository->show(Auth::id());

        if($user->status == StatusType::WAITING_SIMULATION)
        {
            return response()->json(['message' => 'Você já iniciou este processo, por favor vá até o ponto indicado para retirar seu brinde'], 400);
        }

        if($user->status < StatusType::CHECKIN){
            return response()->json(['message' => 'Você precisa executar o processo de checkin antes executar a simulação'], 400);
        }

        if($user->status >= StatusType::SIMULATION){
            return response()->json(['message' => 'Você já executou esse processo'], 400);
        }

        $qrcodeLink = $this->userRepository->generateAccessCode(StatusType::WAITING_SIMULATION, $user->id);

        $user->status = StatusType::WAITING_SIMULATION;
        $user->save();

        SendWhatsappJob::dispatch('simulation', $user, $qrcodeLink);

        return response()->json(["message" => 'Simulação concluída'], 200);

    }

    public function generateNumber()
    {
        $user = $this->userRepository->show(Auth::id());

        if($user->status == StatusType::SENT_NUMBER)
        {
            return response()->json(['message' => 'Você já possui um número da sorte'], 400);
        }

        if($user->status > StatusType::SENT_NUMBER){
            return response()->json(['message' => 'Você já executou esse processo'], 400);
        }

        if($user->status < StatusType::SIMULATION){
            return response()->json(['message' => 'Você precisa executar o processo de simulação antes participar do sorteio'], 400);
        }

        $luckyNumber = $this->userRepository->generateLuckyNumber($user->id);
        $user->status = StatusType::SENT_NUMBER;
        $user->save();

        SendWhatsappJob::dispatch('luckyNumber', $user, null, $luckyNumber->number);

        return response()->json(["message" => 'Número do sorteio enviado'], 200);

    }
}
