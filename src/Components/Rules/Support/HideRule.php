<?php

namespace Polirium\Datatable\Components\Rules\Support;

class HideRule
{
    public function apply(bool $ruleData = false): array
    {
        $output = [];

        if ($ruleData) {
            $output['hide'] = true;
        }

        return $output;
    }
}
