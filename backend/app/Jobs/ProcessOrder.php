<?php

namespace App\Jobs;

use App\Models\Account;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\MeliService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $userId;
    protected $orderId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userId, $orderId)
    {
        $this->userId = $userId;
        $this->orderId = $orderId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(MeliService $meliService)
    {
        $account = Account::where('user_id', $this->userId)->firstOrFail();

        // Obtener la información de la orden
        $order = $meliService->getOrder($account->id, $this->orderId);

        $orderData = [
            'order_id' => $order['id'],
            'account_id' => $account->id,
            'buyer_id' => $order['buyer']['id'],
            'total_amount' => $order['total_amount'],
            'status' => $order['status'],
            'updated_at' => now(),
            'data' => json_encode($order)
        ];

        Log::info($orderData);

        $orderDb = Order::updateOrCreate(
            ['order_id' => $order['id']],
            $orderData
        );

        if (isset($order['order_items'])) {
            foreach ($order['order_items'] as $item) {
                $itemData = [
                    'item_id' => $item['item']['id'],
                    'title' => $item['item']['title'],
                    'unit_price' => $item['unit_price'],
                    'quantity' => $item['quantity'],
                    'order_id' => $orderDb->id,
                    'currency_id' => $item['currency_id'],
                    'full_unit_price' => $item['full_unit_price'],
                    'updated_at' => now(),
                ];

                OrderItem::updateOrInsert(
                    ['item_id' => $itemData['item_id']],
                    $itemData
                );
            }
        }
    }
}
