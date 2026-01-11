<?php

use Laravel\Prompts\{Key, Prompt};
use Polirium\Datatable\Commands\Actions\AskComponentName;

test('input component name', function () {
    Prompt::fake(['New', Key::ENTER]);
    expect(AskComponentName::handle())->toBe('UserTableNew');
});
