<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Order;

use MoonShine\Fields\Number;
use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\Date;
use MoonShine\Fields\DateRange;
use MoonShine\Fields\ID;
use MoonShine\Fields\Text;

class OrderResource extends ModelResource
{
    protected string $model = Order::class;

    protected string $title = 'Orders';

    public function fields(): array
    {
        return [
            Block::make([
                ID::make()->sortable()->showOnExport(),
                BelongsTo::make('Корзина', 'cart', resource: new CartResource())->searchable()->showOnExport(),
                BelongsTo::make('Продукт', 'product', resource: new ProductResource())->searchable()->showOnExport(),
                Number::make('Количество', 'count')->showOnExport(),
                Text::make('Дата изменения', 'updated_at')->sortable()->showOnExport()->showOnExport()->hideOnForm(),
                Text::make('Дата создания', 'created_at')->sortable()->showOnExport()->showOnExport()->hideOnForm(),
            ]),
        ];
    }

    public function rules(Model $item): array
    {
        return [
            'cart_id' => 'required|int|exists:carts,id',
            'product_id' => 'required|int|exists:products,id',
            'count' => 'required|int|max:100',
        ];
    }

    public function filters(): array
    {
        return [

            BelongsTo::make('Корзина', 'cart', resource: new CartResource())->nullable()->searchable(),

            BelongsTo::make('Продукт', 'product', resource: new ProductResource())->nullable()->searchable(),

            DateRange::make('Дата создания', 'created_at')->nullable(),

            Date::make('Дата создания', 'created_at')->nullable(),

        ];
    }
}
