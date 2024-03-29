<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

use MoonShine\Fields\Password;
use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Fields\Text;
use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\Date;
use MoonShine\Fields\DateRange;
use MoonShine\Fields\ID;

class UserResource extends ModelResource
{
    protected string $model = User::class;

    protected string $title = 'Пользователи';

    protected string $column = 'full_name';

    public function fields(): array
    {
        return [
            Block::make([
                ID::make()->sortable()->showOnExport(),
                Text::make('ФИО', 'full_name')->sortable()->showOnExport(),
                Text::make('Телефон', 'telephone')->showOnExport(),
                Text::make('Email', 'email')->showOnExport(),
                Password::make('Пароль', 'password')->showOnForm()->showOnExport(),
                BelongsTo::make('Роль', 'role', resource: new UserRoleResource())->searchable()->showOnExport(),
                Text::make('Дата изменения', 'updated_at')->sortable()->showOnExport()->hideOnForm(),
                Text::make('Дата создания', 'created_at')->sortable()->showOnExport()->hideOnForm(),
            ]),
        ];
    }

    public function rules(Model $item): array
    {
        return [
            'full_name' => 'required|string|max:255',
            'telephone' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|min:6|max:255',
            'role_id' => 'required|int|exists:user_roles,id',
        ];
    }

    public function filters(): array
    {
        return [

            BelongsTo::make('Роль', 'role', resource: new UserRoleResource())->searchable()->nullable(),

            DateRange::make('Дата создания', 'created_at')->nullable(),

            Date::make('Дата создания', 'created_at')->nullable(),

        ];
    }
}
