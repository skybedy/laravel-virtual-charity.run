<?php

namespace App\Exceptions;

use Exception;

class DuplicityTimeException extends Exception
{
    public function __construct()
    {
        $message = "Aktivita s tímto časem již byla nahrána.";

        parent::__construct($message, 0, null);
    }

}
