<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
class DeleteOrderController extends Controller
{
    

public function deleteOrder(Request $request)
{
    $orderId = $request->input('order_id');

    $order = Order::find($orderId);
    if ($order) {
        $order->delete();
        return response()->json(['message' => '订单删除成功']);
    } else {
        return response()->json(['message' => '订单不存在']);
    }
}

}
