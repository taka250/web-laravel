<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MailController;
use App\Http\Controllers\Controller;
use App\Mail\OrderShipped;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\Product;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use App\Models\User;

Route::any('mail/send',[MailController::class,'sendMail']);
   

Route::get('/dashboard', function (Request $request) {
    $products = Product::all(); // 假设使用 Eloquent 模型获取商品列表数据
    $userId = $request->user()->id;
    $orders = Order::where('user_id', $userId)->get(); // 假设使用 Eloquent 模型获取用户的订单数据

    return view('dashboard', compact('products', 'orders'));
   
})->middleware(['auth', 'verified'])->name('dashboard');


require __DIR__.'/auth.php';
