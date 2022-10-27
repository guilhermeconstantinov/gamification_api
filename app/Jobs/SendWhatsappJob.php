<?php

namespace App\Jobs;

use App\Channels\WhatsappChannel;
use App\Notifications\LuckyNumberWhatsapp;
use App\Notifications\NotifyRaffleWhatsapp;
use App\Notifications\RaffleWhatsapp;
use App\Notifications\RegisterWhatsapp;
use App\Notifications\SimulationWhatsapp;
use App\Repositories\Interfaces\WhatsappRepositoryInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;

class SendWhatsappJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    private $qrcode;
    private $user;
    private $type;
    private $luckyNumber;

    /**
     * @var WhatsappRepositoryInterface
     */

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($type, $user, $qrcode = null, $luckyNumber = null)
    {
        $this->type = $type;
        $this->user = $user;
        $this->luckyNumber = $luckyNumber;
        $this->qrcode = $qrcode;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if($this->type == 'register'){
            Notification::channel(WhatsappChannel::class)
                ->send($this->user, new RegisterWhatsapp($this->user->phone, $this->user->name, $this->qrcode));
        }

        if($this->type == 'simulation'){
            Notification::channel(WhatsappChannel::class)
                ->send($this->user, new SimulationWhatsapp($this->user->phone, $this->user->name, $this->qrcode));
        }

        if($this->type == 'luckyNumber'){
            Notification::channel(WhatsappChannel::class)
                ->send($this->user, new LuckyNumberWhatsapp($this->user->phone, $this->user->name, $this->luckyNumber));
        }

        if($this->type == 'raffle'){
            Notification::channel(WhatsappChannel::class)
                ->send($this->user, new RaffleWhatsapp($this->user->phone, $this->user->name, $this->qrcode, $this->luckyNumber));
        }

        if($this->type == 'notifyRaffle'){
            Notification::channel(WhatsappChannel::class)
                ->send($this->user, new NotifyRaffleWhatsapp($this->user->phone, $this->user->name));
        }

    }
}
