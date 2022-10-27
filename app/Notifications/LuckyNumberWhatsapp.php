<?php

namespace App\Notifications;

use App\Channels\Messages\WhatsappMessage;
use App\Channels\WhatsappChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class LuckyNumberWhatsapp extends Notification implements ShouldQueue
{
    use Queueable;

    private $to;
    private $contactName;
    private $luckyNumber;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($to, $contactName, $luckyNumber)
    {
        $this->to = $to;
        $this->contactName = $contactName;
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
            ->type('luckyNumber')
            ->to($this->to)
            ->contactName($this->contactName)
            ->luckyNumber($this->luckyNumber);
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
