<?php

namespace Polirium\Datatable\Tests\Concerns\Components;

use Illuminate\Database\Eloquent\Builder;
use Polirium\Datatable\Tests\Concerns\Models\Dish;

class DishesTable extends BaseDishesTable
{
    public string $tableName = 'dishes-table';

    public function datasource(): Builder
    {
        return Dish::with('category');
    }

    public function relationSearch(): array
    {
        return [
            'category' => [
                'name',
            ],
        ];
    }
}
