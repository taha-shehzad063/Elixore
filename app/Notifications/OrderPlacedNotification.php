<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class OrderPlacedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

 public function toMail($notifiable)
{
    return (new \Illuminate\Notifications\Messages\MailMessage)
        ->subject('Order Confirmation ')
        ->view('emails.order_placed', [
            'order' => $this->order,
            'user' => $notifiable,
        ]);
}

}
