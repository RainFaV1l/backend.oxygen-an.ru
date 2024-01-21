<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\PopularProducts;
use Illuminate\Database\Eloquent\Model;

use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;

class PopularProductResource extends ModelResource
{
    protected string $model = PopularProducts::class;

    protected string $title = 'Популярные';

    public function fields(): array
    {
        return [
            Block::make([
                ID::make()->sortable(),
            ]),
        ];
    }

    public function rules(Model $item): array
    {
        return [];
    }
}
