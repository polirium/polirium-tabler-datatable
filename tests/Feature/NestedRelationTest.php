<?php

use Polirium\Datatable\Tests\Concerns\Components\NestedRelationSearchTable;

use function Polirium\Datatable\Tests\Plugins\livewire;

it('searches data using nested relations', function (string $component, object $params) {
    livewire($component)
        ->call('setTestThemeClass', $params->theme)
        ->set('search', 'Not McDonalds')
        ->assertSee('Not McDonalds');
})->with([
    'tailwind' => [NestedRelationSearchTable::class, (object) ['theme' => \Polirium\Datatable\Themes\Tailwind::class]],
    'bootstrap' => [NestedRelationSearchTable::class, (object) ['theme' => \Polirium\Datatable\Themes\Bootstrap5::class]],
    'daisyui' => [NestedRelationSearchTable::class, (object) ['theme' => \Polirium\Datatable\Themes\DaisyUI::class]],
]);
