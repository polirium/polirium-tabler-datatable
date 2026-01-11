<?php

require __DIR__.'/../Concerns/Components/ComponentsForFilterTest.php';

dataset('filterComponent', [
    'tailwind -> id' => [$component::class, (object) ['theme' => \Polirium\Datatable\Themes\Tailwind::class, 'field' => 'name']],
    'bootstrap -> id' => [$component::class, (object) ['theme' => \Polirium\Datatable\Themes\Bootstrap5::class, 'field' => 'name']],
    'tailwind -> dishes.id' => [$componentJoin::class, (object) ['theme' => \Polirium\Datatable\Themes\Tailwind::class, 'field' => 'dishes.name']],
    'bootstrap -> dishes.id' => [$componentJoin::class, (object) ['theme' => \Polirium\Datatable\Themes\Bootstrap5::class, 'field' => 'dishes.name']],
]);
