<?php

namespace Kernel\Error\Route;

class RouteParamsException extends \Kernel\Error\BaseException{
    public function __construct($message = "the route does not contain the require parameters", \Exception $previous = null)
    {
        parent::__construct($message, 404, $previous);
    }
}
