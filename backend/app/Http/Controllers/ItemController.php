<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;

class ItemController extends Controller
{
    public function getByOrderId($orderId)
    {
        $items = OrderItem::where('order_id', $orderId)->get();
        return response()->json($items);
    }
}
