<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\ProductImages;

use MoonShine\Fields\Image;
use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;

class ProductImageResource extends ModelResource
{
    protected string $model = ProductImages::class;

    protected string $title = 'Изображения';

    public function fields(): array
    {
        return [
            Block::make([
                ID::make()->sortable(),
                BelongsTo::make('Продукт', 'product'),
                Image::make('Изображение', 'image_path')->disk('local')->dir('/public'),
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
}
