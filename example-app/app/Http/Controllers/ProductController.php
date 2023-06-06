<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function deleteProduct(Request $request)
    {
        // 验证请求中的数据
        $request->validate([
            'name' => 'required',
            'quantity' => 'required|integer|min:1',
        ]);

        // 读取请求中的变量
        $productName = $request->input('name');
        $quantity = $request->input('quantity');

        // 查找商品
        $product = Product::where('name', $productName)->first();

        if ($product) {
            // 更新商品数量
            $newQuantity = $product->stock - $quantity;
            if ($newQuantity <= 0) {
                // 如果数量小于等于0，则删除该商品
                $product->delete();
                return response()->json(['message' => '商品删除成功']);
            } else {
                // 更新商品数量
                $product->stock = $newQuantity;
                $product->save();
                return response()->json(['message' => '商品数量更新成功']);
            }
        } else {
            return response()->json(['message' => '商品不存在']);
        }
    }

    public function addToCart(Request $request)
    {
        // 验证请求中的数据
        $request->validate([
            'name' => 'required',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'category' => 'required|in:食品类,家居日用品类,服装鞋帽类,家具类,数码电器类,其他类',
        ]);
    
        // 读取请求中的变量
        $productName = $request->input('name');
        $quantity = $request->input('quantity');
        $price = $request->input('price');
        $category = $request->input('category');
    
        // 创建新的商品
        $product = new Product();
        $product->name = $productName;
        $product->stock = $quantity;
        $product->price = $price;
        $product->category = $category;
        $product->save();
    
        return response()->json(['message' => '商品添加成功']);
    }
    
}

