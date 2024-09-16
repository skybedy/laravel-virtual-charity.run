<?php

namespace App\Exceptions;

use Exception;

class StravaPrivateException extends Exception
{
    public function __construct($message = 'Bohužel, zdá se, že se pokoušíte nahrát aktivitu ze Stravy bez příslušných oprávnění.')
    {
        parent::__construct($message);
    }
}
