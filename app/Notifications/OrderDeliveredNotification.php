<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderDeliveredNotification extends Notification
{
    use Queueable;

    /**
     * Đơn hàng.
     *
     * @var \App\Models\Order
     */
    protected $order;

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
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Đơn hàng #' . $this->order->order_number . ' đã giao thành công')
            ->greeting('Xin chào ' . $notifiable->name . '!')
            ->line('Đơn hàng của bạn đã được giao thành công.')
            ->line('Hãy đánh giá sản phẩm để nhận 100 điểm thưởng cho mỗi đánh giá.')
            ->action('Đánh giá ngay', route('orders.review', $this->order->order_number))
            ->line('Cảm ơn bạn đã mua sắm tại cửa hàng chúng tôi!');
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
            'message' => 'Đơn hàng #' . $this->order->order_number . ' đã giao thành công. Hãy đánh giá sản phẩm để nhận điểm thưởng!',
            'url' => route('orders.review', $this->order->order_number)
        ];
    }
} 