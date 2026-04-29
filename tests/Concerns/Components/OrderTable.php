<?php

namespace Polirium\Datatable\Tests\Concerns\Components;

use Illuminate\Database\Eloquent\Builder;
use Polirium\Datatable\{Column, Facades\PowerGrid, PowerGridComponent, PowerGridFields};
use Polirium\Datatable\Tests\Concerns\Models\Order;

class OrderTable extends PowerGridComponent
{
    public string $tableName = 'testing-order-table';

    public function datasource(): Builder
    {
        return Order::query();
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('name')
            ->add('tax')
            ->add('price')
            ->add('link', fn (Order $order): ?string => $order->link)
            ->add('is_active_label', fn (Order $order): string => $order->price ? 'active' : 'inactive')
            ->add('price_formatted', fn (Order $order): float => $order->price * 100);
    }

    public function columns(): array
    {
        return [
            Column::make('Name', 'name'),
            Column::make('Link', 'link'),
            Column::make('Is Active', 'is_active_label'),
            Column::make('Price', 'price_formatted', 'price'),
            Column::make('Tax', 'tax'),
        ];
    }

    public function setTestThemeClass(string $themeClass): void
    {
        config(['polirium-datatable.theme' => $themeClass]);
    }
}
