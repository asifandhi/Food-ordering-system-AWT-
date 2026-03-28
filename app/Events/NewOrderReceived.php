<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewOrderReceived implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function broadcastOn(): array
    {
        return [
            // Hotelier listens on their own channel
            new Channel('hotelier.' . $this->order->hotelier_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'new.order.received';
    }

    public function broadcastWith(): array
    {
        return [
            'order_id'      => $this->order->id,
            'customer_name' => $this->order->customer->name,
            'grand_total'   => $this->order->grand_total,
            'status'        => $this->order->status,
        ];
    }
}