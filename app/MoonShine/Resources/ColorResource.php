<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Color;

use MoonShine\Fields\Text;
use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\Date;
use MoonShine\Fields\DateRange;
use MoonShine\Fields\ID;

class ColorResource extends ModelResource
{
    protected string $model = Color::class;

    protected string $title = 'Цвета';

    public string $column = 'name';

    protected bool $saveFilterState = true;

    public function fields(): array
    {
        return [
            Block::make([
                ID::make()->sortable()->showOnExport(),
                Text::make('Название', 'name')->sortable()->showOnExport(),
                Text::make('Дата изменения', 'updated_at')->sortable()->showOnExport()->hideOnForm(),
                Text::make('Дата создания', 'created_at')->sortable()->showOnExport()->hideOnForm(),
            ]),
        ];
    }

    public function rules(Model $item): array
    {
        return [
            'name' => 'required|string|max:255'
        ];
    }

    public function filters(): array
    {
        return [

            DateRange::make('Дата создания', 'created_at')->nullable(),

            Date::make('Дата создания', 'created_at')->nullable(),

        ];
    }
}
