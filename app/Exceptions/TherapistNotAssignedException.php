<?php

namespace App\Exceptions;

use Exception;

class TherapistNotAssignedException extends Exception
{
    protected $message = 'Some booking items have no therapist assigned.';
}
