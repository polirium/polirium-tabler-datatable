
<?php

use Polirium\Datatable\Commands\Actions\ListModels;

it('list all Eloquent Models in a directory', function () {
    app()->config->set('polirium-datatable.auto_discover_models_paths', [
        'tests/Concerns/Models',
    ]);

    expect(ListModels::handle())->toBe(
        [
            'Polirium\Datatable\Tests\Concerns\Models\Category',
            'Polirium\Datatable\Tests\Concerns\Models\Chef',
            'Polirium\Datatable\Tests\Concerns\Models\Dish',
            'Polirium\Datatable\Tests\Concerns\Models\Order',
            'Polirium\Datatable\Tests\Concerns\Models\Restaurant',
        ]
    );
});

it('will not list non-Eloquent Models', function () {
    app()->config->set('polirium-datatable.auto_discover_models_paths', [
        'tests/Concerns/Enums', // There are no models in this directory.

    ]);

    expect(ListModels::handle())->toBe([]);
});
