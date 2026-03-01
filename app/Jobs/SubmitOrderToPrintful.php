<?php

namespace App\Jobs;

use App\Services\PrintfulService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Lunar\Models\Order;

class SubmitOrderToPrintful implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Order $order) {}

    public function handle(PrintfulService $printful): void
    {
        $printfulOrderId = $printful->createOrder($this->order);

        if ($printfulOrderId !== null) {
            $meta = $this->order->meta ?? [];
            $meta['printful_order_id'] = $printfulOrderId;
            $this->order->meta = $meta;
            $this->order->save();
        }
    }
}
