<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\CartStoreRequest;
use App\Http\Requests\Api\V1\CartUpdateRequest;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Database\Eloquent\HigherOrderBuilderProxy;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use mysql_xdevapi\Exception;

class CartController extends Controller
{
    public $activeCart;

    public function __construct()
    {

        $this->middleware('auth:api')->except('guestCheckout');

        $this->createCart();

    }

    public function createCart() {

        if(!auth('api')->user()) return;

        $cart = Cart::query()
            ->select('id')
            ->where('status_id', 4)
            ->where('user_id', auth('api')->user()->id)
            ->first();

        if(empty($cart)) {

            $cart = Cart::query()->create([
                'user_id' => auth('api')->user()->id,
                'status_id' => 4,
            ]);

        }

        $this->activeCart = $cart;

    }

    /**
     * @return HigherOrderBuilderProxy|mixed
     */
    public function getCartProducts() {

        $products = [];

        $orders = Order::query()->where('cart_id', $this->activeCart['id'])->get();

        foreach ($orders as $order) {

            $order->product['count'] = $order->count;

            $order->product['preview_image_path'] = asset(Storage::url($order->product['preview_image_path']));

            $products[] = $order->product;

        }

        return $products;

    }

    public function updateCart(CartUpdateRequest $request) {

        $data = $request->validated();

        foreach ($data['products'] as $product) {

            $product['cart_id'] = $this->activeCart->id;

            $order = Order::query()->where('cart_id', $product['cart_id'])->where('product_id', $product['product_id'])->first();

            if(!empty($order)) {

                $product['id'] = $order->id;

                $order->update($product);

                try {

                    Order::query()->update($product);

                } catch (\Throwable $exception) {

                    response()->json(['msg' => 'Синхронизация корзины']);

                }

            } else {

                Order::query()->create($product);

            }



        }

        // Возвращение сообщения об успешном обновлении корзины
        return response()->json(['msg' => 'Успешное обновление корзины']);

    }

    /**
     * @param CartStoreRequest $request
     * @return JsonResponse
     */
    public function addToCart(CartStoreRequest $request) {

        $data = $request->validated();

        $data['cart_id'] = $this->activeCart->id;

        Order::query()->updateOrCreate($data);

        // Возвращение сообщения об успешном добавлении товара в корзину
        return response()->json(['msg' => 'Успешное добавление товара в корзину']);

    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function deleteToCart($id) {

        Order::query()->where('cart_id', $this->activeCart->id)->where('product_id', $id)->delete();

        // Возвращение сообщения об успешном удалении товара из корзины
        return response()->json(['msg' => 'Успешное удаление товара из корзины']);

    }

    /**
     * @return JsonResponse
     */
//    public function clearCart() {
//
//        Order::query()->where('cart_id', $this->activeCart->id)->delete();
//
//        // Возвращение сообщения об успешном удалении товара из корзины
//        return response()->json(['msg' => 'Корзина успешно очищена']);
//
//    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function cancelling($id) {

        $cart = Cart::query()->where('id', $id)->where('user_id', auth('api')->user()->id)->first();

        if($cart->status_id === 2) {

            $cart->update([
                'status_id' => 1
            ]);

            // Возвращение сообщения об успешном удалении товара из корзины
            return response()->json(['msg' => 'Успешное отклонение заказа']);

        }

        return response()->json(['error' => 'Ошибка при отклонении заказа'], 401);

    }

}
