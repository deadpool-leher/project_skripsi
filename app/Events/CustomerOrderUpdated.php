<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CustomerOrderUpdated implements ShouldBroadcastNow
{
    use Dispatchable, SerializesModels;

    public function __construct(public array $orderData, public int $orderId)
    {
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('orders.' . $this->orderId),
        ];
    }

    public function broadcastAs(): string
    {
        return 'CustomerOrderUpdated';
    }

    public function broadcastWith(): array
    {
        return $this->orderData;
    }
}
