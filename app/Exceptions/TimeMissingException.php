<?php

namespace App\Exceptions;

use Exception;

class TimeMissingException extends Exception
{
    public function __construct($message = 'V GPX souboru patrně nejsou uvedeny časové údaje')
    {
        parent::__construct($message);
    }
}
