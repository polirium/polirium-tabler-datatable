<?php

namespace Polirium\Datatable\Concerns;

use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Polirium\Datatable\Column;
use Polirium\Datatable\Components\Filters\FilterCondition;
use Polirium\Datatable\Components\Filters\FilterGroup;

trait FilterBuilder
{
    public bool $filterBuilderEnabled = false;

    public ?FilterGroup $filterBuilderGroup = null;

    public bool $showFilterBuilder = false;

    public array $filterBuilderFields = [];

    public function enableFilterBuilder(): void
    {
        $this->filterBuilderEnabled = true;

        $this->filterBuilderFields = collect($this->columns())
            ->filter(fn ($col) => ! empty($col->field))
            ->map(fn ($col) => [
                'field' => $col->field,
                'label' => $col->title,
                'type' => $this->detectFieldType($col),
            ])
            ->values()
            ->all();

        if ($this->filterBuilderGroup === null) {
            $this->filterBuilderGroup = new FilterGroup(
                id: Str::uuid()->toString(),
                logic: 'AND',
                conditions: []
            );
        }

        // Debug: log to verify enableFilterBuilder was called
        \Log::info('FilterBuilder enabled', [
            'filterBuilderEnabled' => $this->filterBuilderEnabled,
            'fields_count' => count($this->filterBuilderFields),
        ]);
    }

    protected function detectFieldType(Column $column): string
    {
        $field = $column->field;

        $existingFilter = collect($this->filters() ?? [])
            ->first(fn ($f) => data_get($f, 'field') === $field);

        if ($existingFilter) {
            $className = data_get($existingFilter, 'className');

            return match (true) {
                str($className)->contains('FilterNumber') => 'number',
                str($className)->contains('FilterDatePicker') => 'date',
                str($className)->contains('FilterDateTimePicker') => 'datetime',
                str($className)->contains('FilterBoolean') => 'boolean',
                str($className)->contains('FilterSelect') => 'select',
                str($className)->contains('FilterMultiSelect') => 'multi_select',
                default => 'text',
            };
        }

        return 'text';
    }

    public function toggleFilterBuilder(): void
    {
        $this->showFilterBuilder = ! $this->showFilterBuilder;

        // Debug: dispatch event to verify toggle
        \Log::info('FilterBuilder toggled', [
            'showFilterBuilder' => $this->showFilterBuilder,
            'filterBuilderEnabled' => $this->filterBuilderEnabled,
        ]);
    }

    public function updatedFilterBuilderGroup(): void
    {
        $this->applyFilterBuilder();
    }

    public function addFilterBuilderCondition(): void
    {
        $this->filterBuilderGroup->addCondition(
            new FilterCondition(
                id: Str::uuid()->toString(),
                field: '',
                label: '',
                operator: '=',
                value: null,
                valueType: 'text'
            )
        );

        $this->applyFilterBuilder();
    }

    public function removeFilterBuilderCondition(string $conditionId): void
    {
        $this->filterBuilderGroup->removeCondition($conditionId);
        $this->applyFilterBuilder();
    }

    public function updateFilterBuilderCondition(string $conditionId, array $data): void
    {
        $this->filterBuilderGroup->updateCondition($conditionId, $data);
        $this->applyFilterBuilder();
    }

    public function toggleFilterBuilderLogic(): void
    {
        $this->filterBuilderGroup->toggleLogic();
        $this->applyFilterBuilder();
    }

    public function applyFilterBuilder(): void
    {
        $this->resetPage();

        if (! $this->filterBuilderGroup->isValid()) {
            $this->clearFilterBuilder();

            return;
        }

        data_set(
            $this->filters,
            'builder.active',
            $this->filterBuilderGroup->toLivewire()
        );

        $this->persistState('filters');
    }

    public function clearFilterBuilder(): void
    {
        $this->filterBuilderGroup = new FilterGroup(
            id: Str::uuid()->toString(),
            logic: 'AND',
            conditions: []
        );

        data_forget($this->filters, 'builder.active');

        $this->persistState('filters');
    }

    #[Computed]
    public function filterBuilderActiveCount(): int
    {
        return collect(data_get($this->filterBuilderGroup, 'conditions', []))
            ->filter(fn ($c) => $c instanceof FilterCondition && $c->isValid())
            ->count();
    }
}
