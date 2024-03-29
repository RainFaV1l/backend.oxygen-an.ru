<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Http\Resources\CategoryResource;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

use MoonShine\Fields\Image;
use MoonShine\Fields\Number;
use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Fields\Text;
use MoonShine\Fields\Textarea;
use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\Date;
use MoonShine\Fields\DateRange;
use MoonShine\Fields\ID;
use MoonShine\Fields\RangeSlider;
use MoonShine\Fields\Relationships\BelongsToMany;
use MoonShine\Filters\BelongsToFilter;
use MoonShine\Filters\BelongsToManyFilter;
use MoonShine\Filters\DateRangeFilter;
use MoonShine\Filters\SlideFilter;
use MoonShine\Filters\TextFilter;
use MoonShine\Filters\DateFilter;
use MoonShine\Handlers\ExportHandler;

class ProductResource extends ModelResource
{
    protected string $model = Product::class;

    protected string $title = 'Продукты';

//    protected array $with = ['category']; // Eager load

    protected string $sortColumn = 'id'; // Поле сортировки по умолчанию

    protected string $sortDirection = 'DESC'; // Тип сортировки по умолчанию

    protected int $itemsPerPage = 15; // Количество элементов на странице

    public string $column = 'name'; // Поле для отображения значений в связях и хлебных крошках

    protected bool $saveFilterState = true;

    public function fields(): array
    {
        return [
            Block::make([
                ID::make()->sortable()->showOnExport(),
                Text::make('Название', 'name')->showOnExport(),
                Textarea::make('Описание', 'description')->showOnExport()->hideOnIndex(),
                Text::make('Материал', 'material')->showOnExport(),
                Number::make('Цена', 'price')->sortable()->showOnExport(),
                Image::make('Превью', 'preview_image_path')->disk('local')->dir('/public')->showOnExport(),
                Image::make('Размеры', 'size_image_path')->disk('local')->dir('/public')->showOnExport(),
                BelongsTo::make('Цвет', 'color', resource: new ColorResource())->nullable()->searchable()->showOnExport(),
                BelongsTo::make('Тип', 'type', resource: new ProductTypeResource())->nullable()->searchable()->showOnExport(),
                BelongsTo::make('Категория', 'category', resource: new ProductCategoryResource())->required()->searchable()->showOnExport(),
                Text::make('Дата изменения', 'updated_at')->sortable()->showOnExport()->hideOnForm(),
                Text::make('Дата создания', 'created_at')->sortable()->showOnExport()->hideOnForm(),
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
            'preview_image_path' => 'required|image',
            'size_image_path' => 'required|image',
            'color_id' => 'nullable|int|exists:colors,id',
            'category_id' => 'required|int|exists:product_categories,id',
            'type_id' => 'nullable|int|exists:product_types,id',
        ];
    }

    public function filters(): array
    {
        return [
            Text::make('Название', 'name')->nullable(),

            BelongsTo::make('Категория', 'category', resource: new ProductCategoryResource())->searchable()->nullable(),

            BelongsTo::make('Тип', 'type', resource: new ProductTypeResource())->searchable()->nullable(),

            DateRange::make('Дата создания', 'created_at')->nullable(),

            Date::make('Дата создания', 'created_at')->nullable(),

            RangeSlider::make('Цена', 'price')
                ->min(0)
                ->max(1000000)->nullable(),
        ];
    }
}
