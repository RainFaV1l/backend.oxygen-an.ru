<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

use MoonShine\Fields\Image;
use MoonShine\Fields\Number;
use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Fields\Text;
use MoonShine\Fields\Textarea;
use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;

class ProductResource extends ModelResource
{
    protected string $model = Product::class;

    protected string $title = 'Продукты';

//    protected array $with = ['category']; // Eager load

    protected string $sortColumn = 'id'; // Поле сортировки по умолчанию

    protected string $sortDirection = 'DESC'; // Тип сортировки по умолчанию

    protected int $itemsPerPage = 15; // Количество элементов на странице

    public string $column = 'name'; // Поле для отображения значений в связях и хлебных крошках

    public function fields(): array
    {
        return [
            Block::make([
                ID::make()->sortable(),
                Text::make('Название', 'name'),
                Textarea::make('Описание', 'description'),
                Text::make('Материал', 'material'),
                Number::make('Цена', 'price'),
                Image::make('Превью', 'preview_image_path')->disk('local')->dir('/public'),
                Image::make('Размеры', 'size_image_path')->disk('local')->dir('/public'),
                BelongsTo::make('Цвет', 'color', resource: new ColorResource())->nullable(),
                BelongsTo::make('Категория', 'category', resource: new ProductCategoryResource())->required(),
            ]),
        ];
    }

    public function rules(Model $item): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'material' => 'nullable|string|max:2000',
            'price' => 'required|numeric|max:1000000',
            'preview_image_path' => 'nullable|image',
            'size_image_path' => 'nullable|image',
            'color_id' => 'nullable|int|exists:colors,id',
            'category_id' => 'required|int|exists:product_categories,id',
        ];
    }
}
