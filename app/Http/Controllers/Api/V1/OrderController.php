<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\CheckoutRequest;
use App\Mail\Cart\AcceptOrder;
use App\Mail\User\PasswordMail;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\HigherOrderBuilderProxy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class OrderController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api')->except('guestCheckout');
    }

    /**
     * @param $data
     * @return array
     */
    public function createUser(array $data) : array
    {

        $password = Str::random(10);

        $userData = [
            'full_name' => $data['full_name'],
            'telephone' => $data['telephone'],
            'email' => $data['email'],
            'password' =>  Hash::make($password),
        ];

        $user = User::query()->createOrFirst($userData);

        Mail::to($data['email'])->send(new PasswordMail($password));

        $token = auth('api')->login($user);

        $auth = new AuthController();

        $token = $auth->respondWithToken($token);

        $result = [
            'user' => $user,
            'token' => $token,
        ];

        return $result;

//        try {
//
//            DB::transaction(function() use ($data) {
//
//
//
//            });
//
//        } catch (\Throwable $exception) {
//
//            return response()->json(['error' => 'Не удалось зарегистрировать пользователя'], 401);
//
//        }

    }

    /**
     * @param array $data
     * @param int $user_id
     * @param float $total
     * @return Builder|Model
     */
    public function createCart(array $data, int $user_id, float $total) : Builder|Model {

        $cartData = [
            'user_id' => $user_id,
            'status_id' => 2,
            'total' => $total,
            'fio' => $data['full_name'],
            'tel' => $data['telephone'],
            'email' => $data['email'],
            'height' => $data['height'],
            'city' => $data['city'],
            'promotional_code' => $data['promotional_code'] ?: '',
        ];

        return Cart::query()->create($cartData);

    }

    /**
     * @param array $products
     * @param int $cart_id
     * @return void
     */
    public function addProductsToOrder(array $products, int $cart_id) : void {

        foreach ($products as $product) {

            $orderData = [
                'cart_id' => $cart_id,
                'product_id' => $product['product_id'],
                'count' => $product['count'],
            ];

            Order::query()->updateOrCreate($orderData);

        }

    }

    public function guestCheckout(CheckoutRequest $request) {

        // Получение отвалидированных данных
        $data = $request->validated();

        // Получение продуктов
        $products = $data['products'];

        $ids = [];

        foreach ($products as $product) {

            $ids[] = $product['product_id'];

        }

        $product_prices = Product::query()->whereIn('id', $ids)->get();

        // Вычисление итоговой цены
        $total = $product_prices->sum('price');

        // Регистрация пользователя и получение данных
        $user = $this->createUser(data: $data);

        // Оформление заказа

        // Создание корзины и получение данных
        $cart = $this->createCart(data: $data, user_id: $user['user']->id, total: $total);

        // Добавление товаров в заказ
        $this->addProductsToOrder(products: $products, cart_id: $cart->id);

        Mail::to($data['email'])->send(new AcceptOrder($cart, 'успешно оформлен. Ожидайте модерацию администратором.', 'Оформление заказа'));

        // Возвращение сообщения об успешном создании заказа
        return response()->json(['msg' => 'Заказ успешно оформлен', 'token' => $user['token']]);

//        try {
//
//            DB::transaction(function() use ($request) {
//
//
//
//            });
//
//        } catch (\Throwable $exception) {
//
//            return response()->json(['error' => 'Не удалось оформить заказ'], 401);
//
//        }

    }

    /**
     * @param CheckoutRequest $request
     * @return JsonResponse|void
     */
    public function authCheckout(CheckoutRequest $request) {

        // Получение отвалидированных данных
        $data = $request->validated();

        // Получение продуктов
        $products = $data['products'];

        $ids = [];

        // Вычисление итоговой цены
        $total = 0;

        foreach ($products as $product) {

            $productItem = Product::query()->find($product['product_id']);

            $total = $total + ($productItem->price * $product['count']);

        }

        // Оформление заказа

        // Проверка наличия активной корзины
        $cart = Cart::query()->select('id')
            ->where('id', 4)
            ->where('user_id', auth('api')->user()->id)
            ->first();

        // Если есть активная корзина
        if($cart) {

            // Добавление товаров в заказ
            $this->addProductsToOrder(products: $products, cart_id: $cart->id);

        }

        // Создаем корзину, если нет активной
        $cart = $this->createCart(data: $data, user_id: auth('api')->user()->id, total: $total);

        // Добавление товаров в заказ
        $this->addProductsToOrder(products: $products, cart_id: $cart->id);

        Mail::to($data['email'])->send(new AcceptOrder($cart, 'успешно оформлен. Ожидайте модерацию администратором.', 'Оформление заказа'));

        // Возвращение сообщения об успешном создании заказа
        return response()->json(['msg' => 'Заказ успешно оформлен']);

//        try {
//
//            DB::transaction(function() use ($request) {
//
//
//
//            }, 3);
//
//        } catch (\Throwable $exception) {
//
//            return response()->json(['error' => 'Не удалось оформить заказ'], 401);
//
//        }

    }


    /**
     * @return Collection
     */
    public function orders() : Collection {

        $carts = Cart::query()
            ->where('user_id', auth('api')->user()->id)
            ->where('status_id','!=', 4)->orderByDesc('id')->get();

        foreach ($carts as $cart) {

            $cart['status'] = $cart->status;

            foreach ($cart->products as $product) {
                $product['preview_image_path'] = asset(Storage::url($product['preview_image_path']));
            }

            $cart['products'] = $cart->products;

        }

        return $carts;

    }

}
