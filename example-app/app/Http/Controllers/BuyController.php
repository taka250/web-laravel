<?php

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BuyController extends Controller
{
    public function buy(Request $request)
    {
        $items = $request->input('items');
        $total = $request->input('total');
        $date = $request->input('date');

        // 遍历购物车中的商品
        foreach ($items as $item) {
            $productID = $item['id']; // 假设商品ID存储在id字段中
            $quantity = $item['quantity']; // 假设购买数量存储在quantity字段中

            // 创建订单
            $order = new Order();
            $order->order_date = $date;
            $order->order_price = $total;
            $order->quantity = $quantity;
            $order->user_id = Auth::id();
            $order->product_id = $productID;
            $order->save();
        }

        return response()->json(['message' => '购买成功']);
    }
}
