<?php

namespace App\Exceptions;

use Exception;

class NoStravaAuthorizeException extends Exception
{
    public function __construct()
    {
        $message = 'Nejprve je potřeba <a class="underline" href="'.route('authorize_strava').'">AUTORIZOVAT</a> aplikaci na Stravě.';
        parent::__construct($message);
    }
}
