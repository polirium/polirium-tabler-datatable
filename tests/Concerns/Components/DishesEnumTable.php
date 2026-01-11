<?php

namespace Polirium\Datatable\Tests\Concerns\Components;

use Illuminate\Database\Eloquent\Builder;
use Polirium\Datatable\{Column, Facades\Filter, Facades\PowerGrid, PowerGridComponent, PowerGridFields};
use Polirium\Datatable\Tests\Concerns\Enums\Diet;
use Polirium\Datatable\Tests\Concerns\Models\Dish;

class DishesEnumTable extends PowerGridComponent
{
    public string $tableName = 'testing-dishes-enum-table';

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::header()
                ->showToggleColumns()
                ->showSearchInput(),

            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

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

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('name')
            ->add('diet', function ($dish) {
                return Diet::from($dish->diet)->labels();
            });
    }

    public function columns(): array
    {
        $canEdit = true;

        return [
            Column::make('ID', 'id')
                ->searchable()
                ->sortable(),

            Column::make('Stored at', 'storage_room')
                ->sortable(),

            Column::make('Prato', 'name')
                ->searchable()
                ->placeholder('Prato placeholder')
                ->sortable(),

            Column::make('Dieta', 'diet', 'dishes.diet'),

            Column::action('Action'),
        ];
    }

    public function actions(): array
    {
        return [];
    }

    public function filters(): array
    {
        return [
            Filter::enumSelect('diet', 'dishes.diet')
                ->dataSource(Diet::cases()),
        ];
    }

    public function setTestThemeClass(string $themeClass): void
    {
        config(['polirium-datatable.theme' => $themeClass]);
    }
}
