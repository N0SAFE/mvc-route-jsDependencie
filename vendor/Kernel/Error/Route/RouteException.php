<?php

namespace Kernel\Error\Route;

class RouteException extends \Kernel\Error\BaseException {
    public function __construct($message = "Route Not Found", \Exception $previous = null)
    {
        parent::__construct($message, 404, $previous);
    }
}