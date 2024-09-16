<?php

namespace App\Exceptions;

use Exception;

class NoRaceRegistrationException extends Exception
{
    public function __construct($eventId)
    {
        $message = 'K závodu je potřeba nejprve se <a class="underline" href="'.route('registration.create',$eventId).'">ZAREGISTROVAT !!</a>.';

        parent::__construct($message);
    }
}


