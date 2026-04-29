<?php

namespace Polirium\Datatable\Components\Filters;

use Livewire\Wireable;

class FilterGroup implements Wireable
{
    public function __construct(
        public string $id,
        public string $logic = 'AND',
        public array $conditions = [],
    ) {
    }

    public function addCondition(FilterCondition $condition): self
    {
        $this->conditions[] = $condition;

        return $this;
    }

    public function removeCondition(string $conditionId): self
    {
        $this->conditions = collect($this->conditions)
            ->filter(fn ($c) => $c->id !== $conditionId)
            ->values()
            ->all();

        return $this;
    }

    public function updateCondition(string $conditionId, array $data): self
    {
        foreach ($this->conditions as $condition) {
            if ($condition->id === $conditionId) {
                foreach ($data as $key => $value) {
                    $condition->$key = $value;
                }

                break;
            }
        }

        return $this;
    }

    public function toggleLogic(): self
    {
        $this->logic = $this->logic === 'AND' ? 'OR' : 'AND';

        return $this;
    }

    public function isValid(): bool
    {
        return collect($this->conditions)
            ->filter(fn ($c) => $c instanceof FilterCondition && $c->isValid())
            ->isNotEmpty();
    }

    public function toLivewire(): array
    {
        return [
            'id' => $this->id,
            'logic' => $this->logic,
            'conditions' => array_map(
                fn ($c) => $c->toLivewire(),
                $this->conditions
            ),
        ];
    }

    public static function fromLivewire($data): static
    {
        return new self(
            $data['id'],
            $data['logic'] ?? 'AND',
            array_map(
                fn ($c) => FilterCondition::fromLivewire($c),
                $data['conditions'] ?? []
            ),
        );
    }
}
