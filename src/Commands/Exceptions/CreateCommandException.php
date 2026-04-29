<?php

namespace Polirium\Datatable\Commands\Exceptions;

use Exception;

final class CreateCommandException extends Exception
{
    /** @var string */
    protected $message = '';
}
