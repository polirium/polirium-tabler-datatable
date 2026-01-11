<?php

use Polirium\Datatable\Tests\{Concerns\Components\DishesTableWithJoin,
    Concerns\Components\DishesTableWithJoinNames};

    use function Polirium\Datatable\Tests\Plugins\livewire;

it('properly sorts ASC/DESC with: string join column', function (string $component, string $theme) {
    livewire($component)
        ->call('setTestThemeClass', $theme)
        ->set('setUp.footer.perPage', '10')
        ->call('sortBy', 'dishes.id')
        ->set('sortDirection', 'desc')
        ->assertSee('Sopas')
        ->call('sortBy', 'categories.name')
        ->set('sortDirection', 'asc')
        ->assertSee('Acompanhamentos');
})->with([
    'tailwind' => [DishesTableWithJoin::class, \Polirium\Datatable\Themes\Tailwind::class],
    'bootstrap' => [DishesTableWithJoin::class, \Polirium\Datatable\Themes\Bootstrap5::class],
    'daisyui' => [DishesTableWithJoin::class, \Polirium\Datatable\Themes\DaisyUI::class],
]);

it('properly search join column with invalid table', function (string $component, string $theme) {
    livewire($component)
        ->call('setTestThemeClass', $theme)
        ->set('search', 'Pastel de Nata')
        ->assertSee('Pastel')
        ->assertDontSee('Sopas')
        // search in newCategories.name
        ->set('search', 'Peixe')
        ->assertSee('Peixe')
        ->assertDontSee([
            'Acompanhamentos',
            'Sobremesas',
        ]);
})->with([
    'tailwind' => [DishesTableWithJoinNames::class, \Polirium\Datatable\Themes\Tailwind::class],
    'bootstrap' => [DishesTableWithJoinNames::class, \Polirium\Datatable\Themes\Bootstrap5::class],
    'daisyui' => [DishesTableWithJoinNames::class, \Polirium\Datatable\Themes\DaisyUI::class],
]);
