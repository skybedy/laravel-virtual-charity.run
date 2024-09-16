<?php

namespace App\Exceptions;

use Exception;

class StravaPrivateException extends Exception
{
    public function __construct()
    {
        $message = 'Bohužel, zdá se, že se pokoušíte nahrát aktivitu ze Stravy bez příslušných oprávnění, anebo je případně potřeba aplikaci znovu <a class="underline" href="'.route('authorize_strava').'">AUTORIZOVAT</a>.';
        parent::__construct($message);
    }
}
