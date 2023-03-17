<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index($accountId)
    {
        $orders = Order::where('account_id', $accountId)->get();

        return response()->json($orders);
    }

    public function getItems($orderId)
    {
        $order = Order::find($orderId);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $items = $order->items;

        return response()->json($items);
    }
}
