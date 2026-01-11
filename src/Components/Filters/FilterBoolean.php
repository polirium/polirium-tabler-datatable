<?php

namespace Polirium\Datatable\Components\Filters;

use Polirium\Datatable\FilterAttributes\Boolean;

class FilterBoolean extends FilterBase
{
    public string $key = 'boolean';

    public string $trueLabel = 'Yes';

    public string $falseLabel = 'No';

    public function label(string $trueLabel, string $falseLabel): FilterBoolean
    {
        $this->trueLabel = $trueLabel;
        $this->falseLabel = $falseLabel;

        return $this;
    }

    public static function getWireAttributes(string $field, string $title): array
    {
        $configAttributes = config('polirium-datatable.filter_attributes.boolean', Boolean::class);

        /** @var callable $class */
        $class = new $configAttributes();

        return $class($field, $title);
    }
}
