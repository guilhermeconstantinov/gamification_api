<?php

namespace App\Notifications;

use App\Channels\Messages\WhatsappMessage;
use App\Channels\WhatsappChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class RaffleWhatsapp extends Notification implements ShouldQueue
{
    use Queueable;

    private $to;
    private $contactName;
    private $qrcodeLink;
    private $luckyNumber;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($to, $contactName, $qrcodeLink, $luckyNumber)
    {
        $this->to = $to;
        $this->contactName = $contactName;
        $this->qrcodeLink = $qrcodeLink;
        $this->luckyNumber = $luckyNumber;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [WhatsappChannel::class];
    }


    public function toWhatsapp($notifiable): WhatsappMessage
    {
        return (new WhatsappMessage)
            ->type('raffle')
            ->to($this->to)
            ->contactName($this->contactName)
            ->luckyNumber($this->luckyNumber)
            ->qrcodeLink($this->qrcodeLink);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
