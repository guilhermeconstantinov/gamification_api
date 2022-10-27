<?php

namespace App\Repositories\Interfaces;

interface WhatsappRepositoryInterface
{
    public function sendMessage($number, $templateName, $components);

    public function registerMessage($contactName, $number, $qrcodeLink);

    public function simulationMessage($contactName, $number, $qrcodeLink);

    public function luckyNumberMessage($contactName, $number, $luckyNumber);

    public function raffleMessage($contactName, $number, $qrcodeLink);
}
