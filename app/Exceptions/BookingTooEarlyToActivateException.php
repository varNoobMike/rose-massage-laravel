<?php

namespace App\Exceptions;

use Exception;

class BookingTooEarlyToActivateException extends Exception
{
    protected $message = 'Cannot activate booking before scheduled time.';
}
