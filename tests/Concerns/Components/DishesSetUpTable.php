<?php

namespace Polirium\Datatable\Tests\Concerns\Components;

use Illuminate\Database\Eloquent\Builder;
use Polirium\Datatable\{Button, Column, Facades\PowerGrid, PowerGridComponent, PowerGridFields};
use Polirium\Datatable\Tests\Concerns\Models\Dish;

class DishesSetUpTable extends PowerGridComponent
{
    public string $tableName = 'testing-dishes-setup-table';

    public bool $join = false;

    public bool $testHeader = false;

    public bool $testFooter = false;

    public array $testCache = [];

    public function setUp(): array
    {
        $this->showCheckBox();

        if ($this->testCache) {
            return $this->testCache;
        }

        if ($this->testHeader) {
            return [
                PowerGrid::header()
                    ->showSearchInput()
                    ->includeViewOnTop('polirium-datatable::tests.header-top')
                    ->includeViewOnBottom('polirium-datatable::tests.header-bottom'),

            ];
        }

        if ($this->testFooter) {
            return [
                PowerGrid::footer()
                    ->includeViewOnTop('polirium-datatable::tests.footer-top')
                    ->includeViewOnBottom('polirium-datatable::tests.footer-bottom'),
            ];
        }
    }

    public function datasource(): Builder
    {
        if ($this->join) {
            return $this->join();
        }

        return $this->query();
    }

    public function query(): Builder
    {
        return Dish::with('category');
    }

    public function join(): Builder
    {
        return Dish::query()
            ->join('categories', function ($categories) {
                $categories->on('dishes.category_id', '=', 'categories.id');
            })
            ->select('dishes.*', 'categories.name as category_name');
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('name');
    }

    public function columns(): array
    {
        return [
            Column::add()
                ->title(__('ID'))
                ->field('id')
                ->searchable()
                ->sortable(),

            Column::add()
                ->title(__('Prato'))
                ->field('name')
                ->searchable()
                ->sortable(),

            Column::action('Action'),
        ];
    }

    public function actions($row): array
    {
        return [
            Button::make('toggleDetail', 'Toggle Detail')
                ->class('text-center')
                ->toggleDetail($row->id),
        ];
    }
}
