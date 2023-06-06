<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function admin(Request $request)
    {
        $products = Product::all(); // 假设使用 Eloquent 模型获取商品列表数据
        $orders = Order::all();
        $user = Auth::user();

        if ($user->admin) {
            return view('admin', compact('products', 'orders', 'user'));
        } else {
            return redirect()->route('dashboard');
        }
    }
}
