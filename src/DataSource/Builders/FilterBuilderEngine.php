<?php

namespace Polirium\Datatable\DataSource\Builders;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Str;
use Polirium\Datatable\Components\Filters\FilterCondition;
use Polirium\Datatable\Enums\FilterOperator;

class FilterBuilderEngine extends BuilderBase
{
    public function builder(EloquentBuilder|QueryBuilder $builder, string $field, $values): void
    {
        if (! data_get($values, 'active')) {
            return;
        }

        $groupData = data_get($values, 'active');
        $logic = data_get($groupData, 'logic', 'AND');
        $conditions = data_get($groupData, 'conditions', []);

        $builder->where(function ($query) use ($conditions, $logic) {
            foreach ($conditions as $index => $conditionData) {
                $condition = FilterCondition::fromLivewire($conditionData);

                if (! $condition->isValid()) {
                    continue;
                }

                if ($index === 0) {
                    $this->applyCondition($query, $condition);
                } else {
                    $method = $logic === 'AND' ? 'where' : 'orWhere';
                    $query->$method(function ($q) use ($condition) {
                        $this->applyCondition($q, $condition);
                    });
                }
            }
        });
    }

    protected function applyCondition($query, FilterCondition $condition): void
    {
        $operator = FilterOperator::tryFrom($condition->operator);

        if (! $operator) {
            return;
        }

        $field = $condition->field;
        $value = $condition->value;

        if (Str::contains($field, '.')) {
            $this->applyRelationCondition($query, $condition, $operator);

            return;
        }

        match ($operator) {
            FilterOperator::CONTAINS => $query->where($field, 'LIKE', '%' . $value . '%'),
            FilterOperator::CONTAINS_NOT => $query->where($field, 'NOT LIKE', '%' . $value . '%'),
            FilterOperator::IS => $query->where($field, '=', $value),
            FilterOperator::IS_NOT => $query->where($field, '!=', $value),
            FilterOperator::STARTS_WITH => $query->where($field, 'LIKE', $value . '%'),
            FilterOperator::ENDS_WITH => $query->where($field, 'LIKE', '%' . $value),
            FilterOperator::EQUALS => $query->where($field, '=', $value),
            FilterOperator::NOT_EQUALS => $query->where($field, '!=', $value),
            FilterOperator::GREATER_THAN => $query->where($field, '>', $value),
            FilterOperator::GREATER_THAN_OR_EQUAL => $query->where($field, '>=', $value),
            FilterOperator::LESS_THAN => $query->where($field, '<', $value),
            FilterOperator::LESS_THAN_OR_EQUAL => $query->where($field, '<=', $value),
            FilterOperator::IS_EMPTY => $query->where($field, '=', '')->orWhereNull($field),
            FilterOperator::IS_NOT_EMPTY => $query->where($field, '!=', '')->whereNotNull($field),
            FilterOperator::IS_NULL => $query->whereNull($field),
            FilterOperator::IS_NOT_NULL => $query->whereNotNull($field),
            FilterOperator::BEFORE => $query->where($field, '<', $value),
            FilterOperator::AFTER => $query->where($field, '>', $value),
            FilterOperator::BETWEEN => $this->applyBetween($query, $field, $value),
        };
    }

    protected function applyRelationCondition($query, FilterCondition $condition, FilterOperator $operator): void
    {
        $parts = explode('.', $condition->field);
        $relation = $parts[0];
        $field = $parts[1];

        $query->whereHas($relation, function ($q) use ($field, $condition, $operator) {
            $this->applyCondition($q, new FilterCondition(
                id: $condition->id,
                field: $field,
                label: $condition->label,
                operator: $condition->operator,
                value: $condition->value,
                valueType: $condition->valueType
            ));
        });
    }

    protected function applyBetween($query, $field, $value): void
    {
        if (is_array($value) && count($value) === 2) {
            $query->whereBetween($field, [$value[0], $value[1]]);
        }
    }
}
