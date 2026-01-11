<?php

use Polirium\Datatable\Column;
use Polirium\Datatable\Tests\Concerns\Components\DishesTable;

use function Polirium\Datatable\Tests\Plugins\livewire;

$component = new class() extends DishesTable
{
    public function columns(): array
    {
        return [
            Column::add()
                ->title('Id')
                ->field('id')
                ->searchable()
                ->sortable(),

            Column::add()
                ->title('Dish')
                ->field('name')
                ->searchable()
                ->contentClasses('bg-custom-500 text-custom-500')
                ->sortable(),

            Column::action('Action'),
        ];
    }

    public function actionsFromView($row)
    {
        return view('polirium-datatable::tests.actions-view', compact('row'));
    }
};

it('can render actionsFromView property', function (string $component, object $params) {
    livewire($component)
        ->call('setTestThemeClass', $params->theme)
        ->assertSeeInOrder([
            'Dish From Actions View: 1',
            'Dish From Actions View: 2',
            'Dish From Actions View: 3',
            'Dish From Actions View: 4',
            'Dish From Actions View: 5',
            'Dish From Actions View: 6',
        ]);
})->with([
    'tailwind' => [$component::class, (object) ['theme' => \Polirium\Datatable\Themes\Tailwind::class, 'field' => 'name']],
    'bootstrap' => [$component::class, (object) ['theme' => \Polirium\Datatable\Themes\Bootstrap5::class, 'field' => 'name']],
    'daisyui' => [$component::class, (object) ['theme' => \Polirium\Datatable\Themes\DaisyUI::class, 'field' => 'name']],
]);
