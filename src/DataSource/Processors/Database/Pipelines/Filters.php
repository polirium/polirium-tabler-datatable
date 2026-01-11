<?php

namespace Polirium\Datatable\DataSource\Processors\Database\Pipelines;

use Closure;
use Polirium\Datatable\DataSource\Processors\Database\Handlers\{FilterHandler, SearchHandler};
use Polirium\Datatable\PowerGridComponent;

class Filters
{
    public function __construct(protected PowerGridComponent $component) {}

    public function handle(mixed $query, Closure $next): mixed
    {
        (new SearchHandler($this->component))->apply($query);
        (new FilterHandler($this->component))->apply($query);

        return $next($query);
    }
}
