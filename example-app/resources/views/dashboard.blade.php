<x-app-layout>
<main>
        <div class="container">
            <div class="search-container">
                <input type="text" id="product-search" placeholder="搜索商品名称">
                <select id="category-search" onchange="filterByCategory()">
                    <option value="">所有分类</option>
                    <option value="食品类">食品类</option>
                    <option value="家居日用品类">家居日用品类</option>
                    <option value="服装鞋帽类">服装鞋帽类</option>
                    <option value="家具类">家具类</option>
                    <option value="数码电器类">数码电器类</option>
                    <option value="其他类">其他类</option>
                </select>
                <button id="search-btn">搜索</button>
            </div>
            <table border="0" cellspacing="0" id="shoppingcartinfo">
                <thead>
                    <tr>
                        <th width="100px" height="42px">商品ID</th>
                        <th width="400px" height="42px">商品</th>
                        <th width="400px" height="42px">分类</th>
                        <th width="135px" height="42px">单价</th>
                        <th width="230px" height="42px">购买数量</th>
                        <th width="150px" height="42px">小计</th>
                        <th width="200px" height="42px">操作</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                    <tr class="product-row" data-category="{{ $product->category }}">
                        <td width="100px" height="90px" align="center" valign="top" class="pt20 product-id">{{ $product->id }}</td>
                        <td width="400px" height="90px" align="left" valign="top" class="pt20">
                            <div class="product-box">
                                <div class="product-image">
                                    <!-- 商品图片 -->
                                </div>
                                <div class="product-info">
                                    <span class="product-name">{{ $product->name }}</span>
                                </div>
                            </div>
                        </td>
                        <td width="400px" height="90px" align="left" valign="top" class="pt20">
                            <span class="product-category">{{ $product->category }}</span> <!-- 显示商品分类 -->
                        </td>
                        <td width="135px" height="90px" align="center" valign="top" class="price pt20">￥{{ $product->price }}</td>
                        <td width="230px" height="90px" align="center" valign="top" class="purchasenum pt20">
                            <input type="number" min="1" step="1" value="1" class="quantity-input">
                        </td>
                        <td width="150px" height="90px" align="center" valign="top" class="pt20 sumup">￥{{ $product->price }}</td>
                        <td width="200px" height="90px" align="left" valign="top" class="pt20 pl85 operation">
                            <button class="add-to-cart-btn">加入购物车</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="cart-container">
            <h2>购物车</h2>
            <ul id="cart-items"></ul>
            <div class="cart-total">
                <strong>总价: <span id="cart-total-price">￥0.00</span></strong>
            </div>
            <div class="cart-buttons">
                <form id="buy-form" action="/buy" method="POST">
                    @csrf
                    <button type="submit" id="buy-btn">购买</button>
                </form>
                <button id="clear-cart-btn">清空购物车</button>
            </div>
        </div>
        <div class="order-container">
            <h2>我的订单</h2>
            <ul id="order-items">
                @foreach ($orders as $order)
                <li>{{ $order->id }} - {{ $order->created_at }} - {{ $order->order_price}}</li>
                @endforeach
            </ul>
        </div>
</div>
    </main>

</x-app-layout>
<style>

.order-scroll {
    max-height: 200px; /* 设置滚动窗口的最大高度 */
    overflow-y: auto; /* 添加垂直滚动条 */
}

    .product-box {
        border: 1px solid #ccc;
        padding: 10px;
        margin-bottom: 10px;
        background-color: #f9f9f9;
    }

    .product-name {
        font-weight: bold;
        color: #333;
    }

    .add-to-cart-btn {
        background-color: #007bff;
        color: #fff;
        padding: 5px 10px;
        border: none;
        cursor: pointer;
        font-size: 14px;
    }

    .add-to-cart-btn:hover {
        background-color: #0056b3;
    }

    .cart-container {
        position: fixed;
        top: 50px;
        right: 10px;
        width: 300px;
        border: 1px solid #ccc;
        padding: 10px;
        background-color: #fff;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
    }

    .cart-total {
        margin-top: 10px;
    }

    .cart-buttons {
        margin-top: 10px;
        text-align: right;
    }

    .cart-buttons button {
        background-color: #dc3545;
        color: #fff;
        padding: 5px 10px;
        border: none;
        cursor: pointer;
        font-size: 14px;
        margin-left: 5px;
    }

    .cart-buttons button:hover {
        background-color: #c82333;
    }


    .order-container {
            position: fixed;
            top: 300px; /* 调整订单浮窗的位置 */
            right: 10px;
            width: 300px;
            border: 1px solid #ccc;
            padding: 10px;
            background-color: #fff;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
            overflow-y: auto; /* 添加滚动条样式 */
            max-height: 300px; /* 设置最大高度以限制浮窗的高度 */
        }
</style>
<script>
    const addToCartButtons = document.querySelectorAll('.add-to-cart-btn');
    const cartItemsContainer = document.getElementById('cart-items');
    const cartTotalPrice = document.getElementById('cart-total-price');
    const clearCartButton = document.getElementById('clear-cart-btn');
    const buyButton = document.getElementById('buy-btn');
    let totalPrice = 0;
    let cartItems = [];

    addToCartButtons.forEach(button => {
        button.addEventListener('click', () => {
            const productRow = button.parentNode.parentNode;
            const productId = productRow.querySelector('.product-id').textContent;
            const productName = productRow.querySelector('.product-name').textContent;
            const productPrice = parseFloat(productRow.querySelector('.price').textContent.slice(1));
            const quantityInput = productRow.querySelector('.quantity-input');
            const quantity = parseInt(quantityInput.value);
            const productTotalPrice = productPrice * quantity;

            const existingItem = cartItems.find(item => item.id === productId);

            if (existingItem) {
                existingItem.quantity += quantity;
                existingItem.totalPrice += productTotalPrice;
            } else {
                const newItem = {
                    id: productId,
                    name: productName,
                    price: productPrice,
                    quantity: quantity,
                    totalPrice: productTotalPrice,
                };
                cartItems.push(newItem);
            }

            updateCartItems();
            calculateTotalPrice();
        });
    });

    function removeItemFromCart(item) {
        const itemIndex = cartItems.findIndex(cartItem => cartItem.id === item.id);
        if (itemIndex !== -1) {
            cartItems.splice(itemIndex, 1);
            updateCartItems();
            calculateTotalPrice();
        }
    }

    function updateCartItems() {
        cartItemsContainer.innerHTML = '';

        cartItems.forEach(item => {
            const cartItemElement = document.createElement('li');
            cartItemElement.textContent = `ID:${item.id} ${item.name} x${item.quantity}`;
            const deleteButton = document.createElement('button');
            deleteButton.classList.add('delete-item-btn');
            deleteButton.textContent = '删除';
            deleteButton.addEventListener('click', () => {
                removeItemFromCart(item);
            });
            cartItemElement.appendChild(deleteButton);
            cartItemsContainer.appendChild(cartItemElement);
        });
    }

    function calculateTotalPrice() {
        totalPrice = cartItems.reduce((total, item) => total + item.totalPrice, 0);
        cartTotalPrice.textContent = `￥${totalPrice.toFixed(2)}`;
    }

    clearCartButton.addEventListener('click', () => {
        cartItems = [];
        updateCartItems();
        calculateTotalPrice();
    });

    const buyForm = document.getElementById('buy-form');
    buyForm.addEventListener('submit', (event) => {
        event.preventDefault();

        const requestData = {
            items: cartItems.map(item => ({ id: item.id, quantity: item.quantity })),
            total: totalPrice.toFixed(2),
            date: new Date().toISOString().slice(0, 10),
        };

        fetch('/buy', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(requestData),
        })
            .then(response => response.json())
            .then(data => {
                console.log(data.message);
                cartItems = [];
                updateCartItems();
                calculateTotalPrice();
            })
            .catch(error => {
                console.error('购买商品失败:', error);
            });

            location.reload();
    });

    function filterByCategory() {
        const categorySelect = document.getElementById('category-search');
        const selectedCategory = categorySelect.value;
        const productRows = document.querySelectorAll('.product-row');

        productRows.forEach(row => {
            const rowCategory = row.getAttribute('data-category');

            if (selectedCategory === '' || rowCategory === selectedCategory) {
                row.style.display = 'table-row';
            } else {
                row.style.display = 'none';
            }
        });
    }

    const productSearchInput = document.getElementById('product-search');
    const searchButton = document.getElementById('search-btn');

    // 搜索按钮点击事件
    searchButton.addEventListener('click', () => {
        const searchKeyword = productSearchInput.value.trim().toLowerCase();
        const productRows = document.querySelectorAll('.product-row');

        productRows.forEach(row => {
            const productName = row.querySelector('.product-name').textContent.toLowerCase();

            if (productName.includes(searchKeyword)) {
                row.style.display = 'table-row';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>
