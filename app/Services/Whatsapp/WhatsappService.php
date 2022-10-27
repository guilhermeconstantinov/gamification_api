<?php

namespace App\Services\Whatsapp;

use App\Repositories\Interfaces\WhatsappRepositoryInterface;

class WhatsappService implements WhatsappServiceInterface
{
    protected $whatsappRepository;

    public function __construct(WhatsappRepositoryInterface $whatsappRepository)
    {
        $this->whatsappRepository = $whatsappRepository;
    }

}
