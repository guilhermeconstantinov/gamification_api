<?php

namespace App\Notifications;

use App\Channels\Messages\WhatsappMessage;
use App\Channels\WhatsappChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class SimulationWhatsapp extends Notification implements ShouldQueue
{
    use Queueable;

    private $to;
    private $contactName;
    private $qrcodeLink;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($to, $contactName, $qrcodeLink)
    {
        $this->to = $to;
        $this->contactName = $contactName;
        $this->qrcodeLink = $qrcodeLink;
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
            ->type('simulation')
            ->to($this->to)
            ->contactName($this->contactName)
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
