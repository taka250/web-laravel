
<x-app-layout>
<!DOCTYPE html>
<html>
<head>
    <title>商品管理</title>
    <style>
        body {
            font-family: SimSun, Arial, sans-serif;
        }

        .container {
            display: flex;
        }

        .product-list {
            flex: 1;
            margin-right: 20px;
        }

        .product-box {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 10px;
            background-color: #f9f9f9;
        }

        .product-info {
            margin-bottom: 5px;
        }

        .product-info span {
            display: block;
        }

        .cart-container {
            position: fixed;
            top: 50px;
            right: 250px;
            width: 300px;
            border: 1px solid #ccc;
            padding: 10px;
            background-color: #fff;
        }

        .cart-container label,
        .cart-container input {
            display: block;
            margin-bottom: 10px;
        }

        .cart-container button {
            background-color: #007bff;
            color: #fff;
            padding: 5px 10px;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }

        .cart-container button:hover {
            background-color: #0056b3;
        }

        .order-container {
            position: fixed;
            top: 50px;
            right: 10px;
            width: 300px;
            border: 1px solid #ccc;
            padding: 10px;
            background-color: #fff;
        }

        .order-container h2 {
            margin-bottom: 10px;
        }

        .order-container table {
            width: 100%;
        }

        .order-container th,
        .order-container td {
            text-align: center;
            padding: 5px;
            border-bottom: 1px solid #ccc;
        }

        .table-container {
            overflow-y: auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="product-list">
            <h2>商品列表</h2>
            @foreach ($products as $product)
            <div class="product-box">
                <div class="product-info">
                    <span class="product-name">{{ $product->name }}</span>
                    <span class="product-price">价格: {{ $product->price }}</span>
                    <span class="product-stock">库存: {{ $product->stock }}</span>
                </div>
            </div>
            @endforeach
        </div>

        <div class="cart-container">
    <h2>操作台</h2>
    <form id="add-product-form">
        @csrf
        <label for="cart-product-name">商品名称:</label>
        <input type="text" id="cart-product-name" name="name">
        <label for="cart-product-quantity">商品数量:</label>
        <input type="number" id="cart-product-quantity" name="quantity" min="1" step="1">
        <label for="cart-product-price">商品价格:</label>
        <input type="number" id="cart-product-price" name="price" step="0.01">
        <label for="cart-product-category">商品分类:</label>
        <select id="cart-product-category" name="category">
            <option value="">请选择分类</option>
            <option value="食品类">食品类</option>
            <option value="家居日用品类">家居日用品类</option>
            <option value="服装鞋帽类">服装鞋帽类</option>
            <option value="家具类">家具类</option>
            <option value="数码电器类">数码电器类</option>
            <option value="其他类">其他类</option>
        </select>
        <button type="submit">增加商品</button>
    </form>
    <form id="delete-product-form">
        @csrf
        <label for="delete-product-name">商品名称:</label>
        <input type="text" id="delete-product-name" name="name">
        <label for="delete-product-quantity">商品数量:</label>
        <input type="number" id="delete-product-quantity" name="quantity" min="1" step="1">
        <button type="submit">删除商品</button>
    </form>
</div>


    <div class="order-container" style="height: 300px; overflow-y: auto;">
        <h2>订单列表</h2>
        <div class="table-container">
            <table border="0" cellspacing="0" id="order-info">
                <thead>
                    <tr>
                        <th width="100px" height="42px">订单ID</th>
                        <th width="100px" height="42px">用户ID</th>
                        <th width="200px" height="42px">商品编号</th>
                        <th width="100px" height="42px">购买数量</th>
                        <th width="150px" height="42px">购买日期</th>
                        <th width="150px" height="42px">购买价格</th>
                        <th width="100px" height="42px">操作</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($orders as $order)
                <tr>
                    <td width="100px" height="90px" align="center" valign="top">{{ $order->id }}</td>
                    <td width="100px" height="90px" align="center" valign="top">{{ $order->user_id }}</td>
                    <td width="200px" height="90px" align="center" valign="top">{{ $order->product_id}}</td>
                    <td width="100px" height="90px" align="center" valign="top">{{ $order->quantity }}</td>
                    <td width="150px" height="90px" align="center" valign="top">{{ $order->order_date }}</td>
                    <td width="150px" height="90px" align="center" valign="top">{{ $order->order_price }}</td>
                    <td width="100px" height="90px" align="center" valign="top">
                        <form class="delete-order-form" action="/delete_2" method="POST">
                            @csrf
                            <input type="hidden" name="order_id" value="{{ $order->id }}">
                            <button type="submit">删除</button>
                        </form>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    </x-app-layout>
    <script>
        const addProductForm = document.getElementById('add-product-form');
        const deleteProductForm = document.getElementById('delete-product-form');

        // Add event listener to the add product form
        addProductForm.addEventListener('submit', (event) => {
    event.preventDefault(); // 阻止表单默认提交行为

    const productNameInput = document.getElementById('cart-product-name');
    const productQuantityInput = document.getElementById('cart-product-quantity');
    const productPriceInput = document.getElementById('cart-product-price');
    const productCategoryInput = document.getElementById('cart-product-category'); // 新增这一行

    const productName = productNameInput.value;
    const productQuantity = parseInt(productQuantityInput.value);
    const productPrice = parseFloat(productPriceInput.value);
    const productCategory = productCategoryInput.value; // 新增这一行

    // 验证商品分类是否有效
    const validCategories = ['食品类', '家居日用品类', '服装鞋帽类', '家具类', '数码电器类', '其他类'];
    if (!validCategories.includes(productCategory)) {
        console.error('无效的商品分类');
        return;
    }

    const productData = {
        name: productName,
        quantity: productQuantity,
        price: productPrice,
        category: productCategory // 新增这一行
    };

    // 将添加商品的请求发送到 '/add' 路由
    fetch('/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(productData)
    })
        .then(response => {
            if (response.ok) {
                return response.json();
            } else {
                throw new Error('添加商品失败');
            }
        })
        .then(data => {
            // 处理响应数据，例如显示成功消息或执行其他操作
            console.log(data.message);
        })
        .catch(error => {
            // 处理错误，例如显示错误消息或执行其他操作
            console.error('添加商品失败:', error);
        });
        location.reload();
});


        // Add event listener to the delete product form
        deleteProductForm.addEventListener('submit', (event) => {
            event.preventDefault(); // Prevent the form from submitting normally

            const formData = new FormData(deleteProductForm);

            // Send the delete product request to the '/delete' route
            fetch('/delete', {
                method: 'POST',
                body: formData
            })
                .then(response => {
                    if (response.ok) {
                        return response.json();
                    } else {
                        throw new Error('删除商品失败');
                    }
                })
                .then(data => {
                    // Handle the response data, e.g. display success message or perform other actions
                    console.log(data.message);
                })
                .catch(error => {
                    // Handle the error, e.g. display error message or perform other actions
                    console.error('删除商品失败:', error);
                });
        });

        const deleteOrderForms = document.querySelectorAll('.delete-order-form');
        deleteOrderForms.forEach(form => {
            form.addEventListener('submit', (event) => {
                event.preventDefault();
                const orderIdInput = form.querySelector('input[name="order_id"]');
                const orderId = orderIdInput.value;
                deleteOrder(orderId);
            });
        });

        // Function to delete an order
        function deleteOrder(orderId) {
            fetch('/delete_2', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ order_id: orderId })
            })
            .then(response => {
                if (response.ok) {
                    // Order deleted successfully
                    console.log('Order deleted successfully');
                    location.reload(); // 刷新页面
                } else {
                    // Failed to delete order
                    console.error('Failed to delete order');
                }
            })
            .catch(error => {
                console.error('Error deleting order:', error);
            });
        }
    </script>
</body>
</html>
