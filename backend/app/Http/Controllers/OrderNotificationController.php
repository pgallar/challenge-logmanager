<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessOrder;
use App\Models\Order as ModelsOrder;
use App\Order;
use App\Services\MeliService;
use Illuminate\Http\Request;

class OrderNotificationController extends Controller
{
    public function handle(Request $request)
    {
        $json = $request->getContent();
        $notification = json_decode($json, true);

        // check if notification is from orders_v2 topic
        if (isset($notification['topic']) && $notification['topic'] === 'orders_v2') {
            $orderId = $notification['resource']['id'];

            // dispatch job to process the order
            ProcessOrder::dispatch($orderId)->onQueue('orders');
        }

        return response()->json(['message' => 'Notification received']);
    }
}
