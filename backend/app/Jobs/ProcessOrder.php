<?php

namespace App\Jobs;

use App\Models\Order;
use App\Models\OrderItem;
use App\Services\MeliService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $orderId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($orderId)
    {
        $this->orderId = $orderId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(MeliService $meliService)
    {
        // Obtener la información de la orden
        $order = $meliService->getOrder($this->orderId);
        $order = Order::updateOrInsert(
            ['order_id' => $order['order_id']],
            $order
        );

        // Obtener la información de los items de la orden
        $orderItems = $meliService->getOrderItems($this->orderId);

        foreach ($orderItems as $item) {
            $itemData = [
                'item_id' => $item['item']['id'],
                'title' => $item['item']['title'],
                'price' => $item['item']['price'],
                'quantity' => $item['quantity'],
                'order_id' => $order['id'],
                'updated_at' => now(),
            ];

            OrderItem::updateOrInsert(
                ['item_id' => $itemData['item_id']],
                $itemData
            );
        }
    }
}
