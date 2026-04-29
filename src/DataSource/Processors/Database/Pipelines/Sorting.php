<?php

namespace Polirium\Datatable\DataSource\Processors\Database\Pipelines;

use Closure;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Str;
use Polirium\Datatable\PowerGridComponent;

class Sorting
{
    public function __construct(protected PowerGridComponent $component)
    {
    }

    public function handle(mixed $query, Closure $next): mixed
    {
        if (! ($query instanceof EloquentBuilder || $query instanceof MorphToMany || $query instanceof QueryBuilder)) {
            return $next($query);
        }

        if (filled($this->component->sortField)) {
            if ($this->component->multiSort) {
                $this->applyMultipleSort($query);
            } else {
                $sortField = $this->makeSortField($this->component->sortField);

                if ($this->isValidSortField($sortField)) {
                    $query->orderBy(
                        $sortField,
                        $this->component->sortDirection
                    );
                }
            }
        }

        return $next($query);
    }

    private function applyMultipleSort(EloquentBuilder|MorphToMany|QueryBuilder $results): void
    {
        foreach ($this->component->sortArray as $sortField => $direction) {
            $field = $this->makeSortField($sortField);

            if ($this->isValidSortField($field)) {
                $results->orderBy($field, $direction);
            }
        }
    }

    private function makeSortField(string $sortField): string
    {
        if (Str::of($sortField)->contains('.') || $this->component->ignoreTablePrefix) {
            return $sortField;
        }

        return $this->component->currentTable . '.' . $sortField;
    }

    /**
     * Validate that a sort field is a real DB column (not a computed/virtual field).
     */
    private function isValidSortField(string $sortField): bool
    {
        $field = Str::afterLast($sortField, '.');

        // Reject fields ending with _formatted — these are computed, not DB columns
        if (Str::endsWith($field, '_formatted')) {
            return false;
        }

        return true;
    }
}
