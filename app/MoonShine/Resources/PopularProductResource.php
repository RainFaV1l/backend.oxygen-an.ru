<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\PopularProducts;
use Illuminate\Database\Eloquent\Model;

use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\Date;
use MoonShine\Fields\DateRange;
use MoonShine\Fields\ID;
use MoonShine\Fields\Text;

class PopularProductResource extends ModelResource
{
    protected string $model = PopularProducts::class;

    protected string $title = 'Популярные';

    protected bool $saveFilterState = true;

    public function fields(): array
    {
        return [
            Block::make([
                ID::make()->sortable()->showOnExport(),
                BelongsTo::make('Продукт', 'product', resource: new ProductResource())->searchable()->showOnExport(),
                BelongsTo::make('Популярный продукт', 'popularProduct', function ($item) {
                    return $item->name;
                })->searchable()->showOnExport(),
                Text::make('Дата изменения', 'updated_at')->sortable()->showOnExport()->hideOnForm(),
                Text::make('Дата создания', 'created_at')->sortable()->showOnExport()->hideOnForm(),
            ]),
        ];
    }

    public function rules(Model $item): array
    {
        return [
            'product_id' => 'required|int|exists:products,id',
            'popular_product_id' => 'required|int|exists:popular_products,id'
        ];
    }

    public function filters(): array
    {
        return [

            BelongsTo::make('Продукт', 'product', resource: new ProductResource())->searchable()->nullable(),

            BelongsTo::make('Популярный продукт', 'popularProduct', function ($item) {
                return $item->name;
            })->searchable()->nullable(),

            DateRange::make('Дата создания', 'created_at')->nullable(),

            Date::make('Дата создания', 'created_at')->nullable(),

        ];
    }
}
