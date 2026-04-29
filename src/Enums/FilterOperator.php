<?php

namespace Polirium\Datatable\Enums;

enum FilterOperator: string
{
    // Text operators
    case CONTAINS = 'contains';
    case CONTAINS_NOT = 'contains_not';
    case IS = 'is';
    case IS_NOT = 'is_not';
    case STARTS_WITH = 'starts_with';
    case ENDS_WITH = 'ends_with';

    // Number operators
    case EQUALS = '=';
    case NOT_EQUALS = '!=';
    case GREATER_THAN = '>';
    case GREATER_THAN_OR_EQUAL = '>=';
    case LESS_THAN = '<';
    case LESS_THAN_OR_EQUAL = '<=';

    // Null/Empty operators
    case IS_EMPTY = 'is_empty';
    case IS_NOT_EMPTY = 'is_not_empty';
    case IS_NULL = 'is_null';
    case IS_NOT_NULL = 'is_not_null';

    // Date operators
    case BEFORE = 'before';
    case AFTER = 'after';
    case BETWEEN = 'between';

    public function types(): array
    {
        return match ($this) {
            self::CONTAINS, self::CONTAINS_NOT, self::IS, self::IS_NOT,
            self::STARTS_WITH, self::ENDS_WITH => ['text', 'string'],

            self::EQUALS, self::NOT_EQUALS, self::GREATER_THAN, self::GREATER_THAN_OR_EQUAL,
            self::LESS_THAN, self::LESS_THAN_OR_EQUAL => ['number', 'integer', 'decimal'],

            self::IS_EMPTY, self::IS_NOT_EMPTY, self::IS_NULL, self::IS_NOT_NULL
                => ['text', 'string', 'number', 'integer', 'decimal', 'date'],

            self::BEFORE, self::AFTER, self::BETWEEN => ['date', 'datetime'],
        };
    }

    public function label(): string
    {
        return __('polirium-datatable::datatable.filter_builder.operators.' . $this->value);
    }

    public static function forType(string $type): array
    {
        return collect(self::cases())
            ->filter(fn ($op) => in_array($type, $op->types()))
            ->values()
            ->all();
    }
}
