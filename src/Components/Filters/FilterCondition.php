<?php

namespace Polirium\Datatable\Components\Filters;

use Livewire\Wireable;

class FilterCondition implements Wireable
{
    public function __construct(
        public string $id,
        public string $field,
        public string $label,
        public string $operator,
        public mixed $value = null,
        public ?string $valueType = null,
        public ?array $valueConfig = [],
    ) {
    }

    public function isValid(): bool
    {
        if (empty($this->field)) {
            return false;
        }

        if (empty($this->operator)) {
            return false;
        }

        // Null operators don't need value
        if (in_array($this->operator, ['is_null', 'is_not_null', 'is_empty', 'is_not_empty'])) {
            return true;
        }

        // Other operators need value
        return $this->value !== null && $this->value !== '';
    }

    public function toLivewire(): array
    {
        return [
            'id' => $this->id,
            'field' => $this->field,
            'label' => $this->label,
            'operator' => $this->operator,
            'value' => $this->value,
            'valueType' => $this->valueType,
            'valueConfig' => $this->valueConfig,
        ];
    }

    public static function fromLivewire($data): static
    {
        return new self(
            $data['id'],
            $data['field'],
            $data['label'],
            $data['operator'],
            $data['value'] ?? null,
            $data['valueType'] ?? null,
            $data['valueConfig'] ?? [],
        );
    }
}
