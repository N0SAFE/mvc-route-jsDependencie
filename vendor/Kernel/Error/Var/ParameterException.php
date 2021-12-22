<?php

namespace Kernel\Error\Var;

class ParameterException extends \Kernel\Error\BaseException
{
    public function __construct($message = "an argument is missing or don't exist", \Exception $previous = null)
    {
        parent::__construct($message, 404, $previous);
    }
}
