<x-mail::message>
# Описание

Ваш заказ №{{ $order->id }} {{ $message }}<br><br>
Состав вашего заказа:<br><br>
    @foreach($order->orders as $order)
        Название продукта: {{ $order->product->name }}.<br>
        Цена за штуку: {{ $order->product->price }}.<br>
        Количество: {{ $order->count }}<br>
        <hr>
    @endforeach

Спасибо за оформление заказа, ваш<br>
{{ config('app.name') }}
</x-mail::message>
