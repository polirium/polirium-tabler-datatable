<?php

namespace Polirium\Datatable\Facades;

use Illuminate\Support\Facades\Facade;
use Polirium\Datatable\Components\SetUp\{Cache, Detail, Exportable, Footer, Header, Lazy, Responsive};
use Polirium\Datatable\{PowerGridFields, PowerGridManager};

/**
 * @method static PowerGridFields fields()
 * @method static Header header()
 * @method static Footer footer()
 * @method static Lazy lazy()
 * @method static Detail detail()
 * @method static Cache cache()
 * @method static Exportable exportable(string $fileName)
 * @method static Responsive responsive()
 */
class PowerGrid extends Facade
{
    public static function getFacadeAccessor(): string
    {
        return PowerGridManager::class;
    }
}
