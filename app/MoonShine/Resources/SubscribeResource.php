<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Subscribe;

use MoonShine\Fields\Text;
use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\Date;
use MoonShine\Fields\DateRange;
use MoonShine\Fields\ID;

class SubscribeResource extends ModelResource
{
    protected string $model = Subscribe::class;

    protected string $title = 'Subscribes';

    public function fields(): array
    {
        return [
            Block::make([
                ID::make()->sortable()->showOnExport(),
                Text::make('email')->sortable()->showOnExport(),
                Text::make('Дата изменения', 'updated_at')->sortable()->showOnExport()->hideOnForm(),
                Text::make('Дата создания', 'created_at')->sortable()->showOnExport()->hideOnForm(),
            ]),
        ];
    }

    public function rules(Model $item): array
    {
        return [
            'email' => 'required|string|email|max:255|min:5|unique:subscribes,email'
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
