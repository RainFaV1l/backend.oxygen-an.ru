<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Mail\Cart\AcceptOrder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Cart;

use Illuminate\Support\Facades\Mail;
use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Fields\Text;
use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\Date;
use MoonShine\Fields\DateRange;
use MoonShine\Fields\ID;

class CartResource extends ModelResource
{
    protected string $model = Cart::class;

    protected string $title = 'Корзины';

    public function fields(): array
    {
        return [
            Block::make([
                ID::make()->sortable()->showOnExport(),
                BelongsTo::make('Пользователь', 'user')->searchable()->showOnExport(),
                BelongsTo::make('Статус', 'status', resource: new CartStatusResource())->searchable()->showOnExport(),
                Text::make('Итоговая сумма', 'total')->showOnExport(),
                Text::make('ФИО', 'fio')->showOnExport(),
                Text::make('Телефон', 'tel')->showOnExport(),
                Text::make('Email', 'email')->showOnExport(),
                Text::make('Высота', 'height')->showOnExport(),
                Text::make('Город', 'city')->showOnExport(),
                Text::make('Промокод', 'promotional_code')->showOnExport(),
                Text::make('Дата изменения', 'updated_at')->sortable()->showOnExport()->hideOnIndex()->hideOnForm(),
                Text::make('Дата создания', 'created_at')->sortable()->showOnExport()->hideOnForm(),
            ]),
        ];
    }


    public function afterUpdated(Model $item): Model
    {
        if((int) $item->status_id === 1) {
            Mail::to($item->email)->send(new AcceptOrder($item, 'отклонен. Обратитесь в поддержку за уточнением причины отклонения.', 'Статус заказа'));
        }
        if((int) $item->status_id === 3) {
            Mail::to($item->email)->send(new AcceptOrder($item, 'успешно оплачен и принят в обработку.', 'Статус заказа'));
        }
        if((int) $item->status_id === 5) {
            Mail::to($item->email)->send(new AcceptOrder($item, 'успешно доставлен. Спасибо за покупку!', 'Статус заказа'));
        }
        return $item;
    }


    public function rules(Model $item): array
    {
        return [
            'user_id' => 'required|int|exists:users,id',
            'status_id' => 'required|int|exists:cart_statuses,id',
            'total' => 'required|numeric|max:10000000',
            'fio' => 'required|string|max:255',
            'tel' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'height' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'promotional_code' => 'nullable|string|max:255',
        ];
    }

    public function filters(): array
    {
        return [

            BelongsTo::make('Пользователь', 'user')->searchable()->nullable()->showOnExport()->showOnExport(),

            BelongsTo::make('Статус', 'status', resource: new CartStatusResource())->searchable()->nullable()->showOnExport()->showOnExport(),

            DateRange::make('Дата создания', 'created_at')->nullable(),

            Date::make('Дата создания', 'created_at')->nullable(),

        ];
    }
}
