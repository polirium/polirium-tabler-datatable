<?php

namespace Polirium\Datatable\Tests\Concerns\Components;

use Polirium\Datatable\{Button, Facades\PowerGrid};
use Polirium\Datatable\Facades\Rule;

class RulesDetailRowTable extends DishTableBase
{
    public string $tableName = 'testing-rules-detail-row-table';

    public function setUp(): array
    {
        config()->set('livewire.inject_morph_markers', false);

        return [
            PowerGrid::header()
                ->showToggleColumns()
                ->showSearchInput(),

            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),

            PowerGrid::detail()
                ->view('polirium-datatable::tests.detail')
                ->options(['name' => 'Luan'])
                ->showCollapseIcon(),
        ];
    }

    public function actions($row): array
    {
        return [
            Button::add('edit')
                ->slot('<div id="edit">Toggle</div>')
                ->class('text-center')
                ->toggleDetail($row->id),
        ];
    }

    public function actionRules(): array
    {
        return [
            Rule::rows()
                ->when(fn ($dish) => $dish->id == 1)
                ->detailView('components.detail-rules', ['test' => 1]),
        ];
    }
}
