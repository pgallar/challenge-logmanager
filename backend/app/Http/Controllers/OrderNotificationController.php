<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessOrder;
use Illuminate\Http\Request;

class OrderNotificationController extends Controller
{
    public function handle(Request $request)
    {
        $json = $request->getContent();
        $notification = json_decode($json, true);

        // check if notification is from orders_v2 topic
        if (isset($notification['topic']) && $notification['topic'] === 'orders_v2') {
            $orderId = str_replace("/orders/", "", $notification['resource']);
            $userId = $notification['user_id'];

            // dispatch job to process the order
            ProcessOrder::dispatch($userId, $orderId)->onQueue('orders');

            return response()->json(['message' => 'Notification received']);
        }

        return response()->json(['message' => 'No data from notification received']);
    }
}
