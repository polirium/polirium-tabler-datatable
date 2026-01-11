<?php

namespace Polirium\Datatable\Facades;

use Illuminate\Support\Facades\Facade;
use Polirium\Datatable\Components\Rules\{RuleActions, RuleCheckbox, RuleEditOnClick, RuleRadio, RuleRows, RuleToggleable};

/**
 * @method static RuleActions button(string $action)
 * @method static RuleToggleable toggleable(string $action)
 * @method static RuleEditOnClick editOnClick(string $action)
 * @method static RuleRows rows()
 * @method static RuleCheckbox checkbox()
 * @method static RuleRadio radio()
 *
 * @see RuleManager
 */
class Rule extends Facade
{
    public static function getFacadeAccessor(): string
    {
        return 'rule';
    }
}
