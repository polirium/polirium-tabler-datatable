<?php

namespace Polirium\Datatable\Exceptions;

class TableNameCannotCalledDefault extends \Exception
{
    public function __construct()
    {
        parent::__construct('The public property $tableName cannot be called: "default" and must be unique per table');
    }
}
