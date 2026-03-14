<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Notification;

class NewOrderNotification extends Notification
{
    use Queueable;

    public $order;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('New Order Received - ' . $this->order->order_number)
                    ->greeting('Hello Admin!')
                    ->line('A new order has been placed.')
                    ->line('Order Number: ' . $this->order->order_number)
                    ->line('Customer: ' . $this->order->user->name)
                    ->line('Total Amount: ₹' . number_format($this->order->grand_total, 2))
                    ->action('View Order', url('/admin/orders/' . $this->order->id))
                    ->line('Please process this order as soon as possible.');
    }

    /**
     * Get the database representation of the notification.
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'customer_name' => $this->order->user->name,
            'total_amount' => $this->order->grand_total,
            'status' => $this->order->status,
            'message' => 'New order #' . $this->order->order_number . ' has been placed by ' . $this->order->user->name
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'customer_name' => $this->order->user->name,
            'total_amount' => $this->order->grand_total,
            'message' => 'New order received'
        ];
    }
}