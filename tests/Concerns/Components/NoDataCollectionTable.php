<?php

namespace Polirium\Datatable\Tests\Concerns\Components;

use Illuminate\Support\Collection;
use Polirium\Datatable\{Column, Facades\PowerGrid, PowerGridComponent, PowerGridFields};

class NoDataCollectionTable extends PowerGridComponent
{
    public string $tableName = 'testing-no-data-collection-table';

    public function datasource(): Collection
    {
        return collect([]);
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
                ->title(__('Name'))
                ->field('name')
                ->searchable()
                ->sortable(),

            Column::action('Action'),
        ];
    }

    public function actions($row): array
    {
        return [];
    }

    public function filters(): array
    {
        return [];
    }

    public function setTestThemeClass(string $themeClass): void
    {
        config(['polirium-datatable.theme' => $themeClass]);
    }
}
