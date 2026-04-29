<?php

namespace Polirium\Datatable\Components\SetUp;

use Livewire\Wireable;

/** @codeCoverageIgnore */
final class Responsive implements Wireable
{
    public string $name = 'responsive';

    public const ACTIONS_COLUMN_NAME = 'actions';

    public const CHECKBOX_COLUMN_NAME = 'checkbox';

    public array $fixedColumns = ['id', self::CHECKBOX_COLUMN_NAME, self::ACTIONS_COLUMN_NAME];

    public array $sortOrder = [];

    public bool $mobileCardView = true;

    public int $mobileBreakpoint = 768;

    public array $mobileHiddenColumns = [];

    public function fixedColumns(string ...$columnNames): static
    {
        $this->fixedColumns = [...$columnNames];

        return $this;
    }

    public function sortOrder(string|array ...$columnNames): static
    {
        if (is_array(data_get($columnNames, '0'))) {
            $columnNames = $columnNames[0];
        }

        foreach ((array) $columnNames as $key => $column) {
            if (is_int($key)) {
                $this->sortOrder[$column] = $key + 1;

                continue;
            }

            $this->sortOrder[$key] = $column;
        }

        return $this;
    }

    public function mobileCardView(bool $enabled = true): static
    {
        $this->mobileCardView = $enabled;

        return $this;
    }

    public function mobileBreakpoint(int $breakpoint): static
    {
        $this->mobileBreakpoint = $breakpoint;

        return $this;
    }

    public function hideOnMobile(string ...$columnNames): static
    {
        $this->mobileHiddenColumns = [...$columnNames];

        return $this;
    }

    public function toLivewire(): array
    {
        return (array) $this;
    }

    public static function fromLivewire($value)
    {
        return $value;
    }
}
