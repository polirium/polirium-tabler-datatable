<?php

use Laravel\Prompts\{Key, Prompt};
use Polirium\Datatable\Commands\Actions\AskComponentDatasource;

test('selecting component data source', function () {
    Prompt::fake([Key::DOWN, Key::ENTER]);
    expect(AskComponentDatasource::handle())->toBe('QUERY_BUILDER');
});
