<?php

namespace App\Exceptions;

use Exception;

class SpaIsClosedException extends Exception
{
    protected $message = 'Spa is closed during this day.';
}
