<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Color;
use App\Models\PopularProducts;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductImages;
use App\Models\ProductType;
use App\Models\User;
use App\Models\UserRole;
use App\MoonShine\Resources\ColorResource;
use App\MoonShine\Resources\PopularProductResource;
use App\MoonShine\Resources\ProductCategoryResource;
use App\MoonShine\Resources\ProductImageResource;
use App\MoonShine\Resources\ProductResource;
use App\MoonShine\Resources\ProductTypeResource;
use App\MoonShine\Resources\UserResource;
use App\MoonShine\Resources\UserRoleResource;
use MoonShine\Models\MoonshineUser;
use MoonShine\Models\MoonshineUserRole;
use MoonShine\Providers\MoonShineApplicationServiceProvider;
use MoonShine\MoonShine;
use MoonShine\Menu\MenuGroup;
use MoonShine\Menu\MenuItem;
use MoonShine\Resources\MoonShineUserResource;
use MoonShine\Resources\MoonShineUserRoleResource;

class MoonShineServiceProvider extends MoonShineApplicationServiceProvider
{
    protected function resources(): array
    {
        return [];
    }

    protected function pages(): array
    {
        return [];
    }

    protected function menu(): array
    {
        return [
            MenuGroup::make(static fn() => __('moonshine::ui.resource.system'), [
                MenuItem::make(
                    static fn() => __('moonshine::ui.resource.admins_title'),
                    new MoonShineUserResource()
                )->badge(fn() => MoonshineUser::query()->count()),
                MenuItem::make(
                    static fn() => __('moonshine::ui.resource.role_title'),
                    new MoonShineUserRoleResource()
                )->badge(fn() => MoonshineUserRole::query()->count()),
            ]),
            MenuGroup::make('Продукты', [
                MenuItem::make('Продукт', new ProductResource())->icon('heroicons.outline.shopping-bag')->badge(fn() => Product::query()->count()),
                MenuItem::make('Цвет', new ColorResource())->icon('heroicons.outline.sun')->badge(fn() => Color::query()->count()),
                MenuItem::make('Категория', new ProductCategoryResource())->icon('heroicons.outline.tag')->badge(fn() => ProductCategory::query()->count()),
                MenuItem::make('Популярные', new PopularProductResource())->icon('heroicons.outline.bookmark')->badge(fn() => PopularProducts::query()->count()),
                MenuItem::make('Изображения', new ProductImageResource())->icon('heroicons.outline.photo')->badge(fn() => ProductImages::query()->count()),
                MenuItem::make('Типы', new ProductTypeResource())->icon('heroicons.outline.chart-bar')->badge(fn() => ProductType::query()->count()),
            ]),
            MenuGroup::make('Пользователи', [
                MenuItem::make('Пользователь', new UserResource())->icon('heroicons.outline.user')->badge(fn() => User::query()->count()),
                MenuItem::make('Роль', new UserRoleResource())->icon('heroicons.outline.adjustments-vertical')->badge(fn() => UserRole::query()->count()),
            ]),
            MenuGroup::make('Заказы', [

            ]),
        ];
    }

    /**
     * @return array{css: string, colors: array, darkColors: array}
     */
    protected function theme(): array
    {
        return [];
    }
}
