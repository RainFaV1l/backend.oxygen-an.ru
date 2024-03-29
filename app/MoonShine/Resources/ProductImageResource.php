<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\ProductImages;

use MoonShine\Fields\Image;
use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\Date;
use MoonShine\Fields\DateRange;
use MoonShine\Fields\ID;
use MoonShine\Fields\Text;

class ProductImageResource extends ModelResource
{
    protected string $model = ProductImages::class;

    protected string $title = 'Изображения';

    protected bool $saveFilterState = true;

    public function fields(): array
    {
        return [
            Block::make([
                ID::make()->sortable(),
                BelongsTo::make('Продукт', 'product')->searchable(),
                Image::make('Изображение', 'image_path')->disk('local')->dir('/public'),
                Text::make('Дата изменения', 'updated_at')->sortable()->showOnExport()->hideOnForm(),
                Text::make('Дата создания', 'created_at')->sortable()->showOnExport()->hideOnForm(),
            ]),
        ];
    }

    public function rules(Model $item): array
    {
        return [
            'product_id' => 'required|int|exists:products,id',
            'image_path' => 'nullable|image'
        ];
    }

    public function filters(): array
    {
        return [

            BelongsTo::make('Продукт', 'product', resource: new ProductResource())->searchable()->nullable(),

            DateRange::make('Дата создания', 'created_at')->nullable(),

            Date::make('Дата создания', 'created_at')->nullable(),

        ];
    }
}
