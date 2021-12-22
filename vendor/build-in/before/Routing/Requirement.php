<?php

namespace Build_in\Routing;

use Kernel\Error\Route\RouteParamsException;

class Requirement {
    // parse the d(d+ d-), i(i+ i-), s(), b(), f(f+ f-), o()
    static function parseForRequirements(string|array|null $requirements, string $value)
    {
        switch(true){
            case $requirements == "d+" || (is_array($requirements) && in_array("d+", $requirements)):
                return is_numeric($value) ? ((int)$value >= 0 ? (int)$value : null) : null;
            case $requirements == "d-" || (is_array($requirements) && in_array("d-", $requirements)):
                return is_numeric($value) ? ((int)$value < 0 ? (int)$value : null) : null;
            case $requirements == "d" || (is_array($requirements) && in_array("d", $requirements)):
                return is_numeric($value) ? (int)$value : null;
            case $requirements == "s" || (is_array($requirements) && in_array("s", $requirements)):
                return (string)$value;
            case $requirements == "b" || (is_array($requirements) && in_array("b", $requirements)):
                return (bool)$value;
            case $requirements == "f" || (is_array($requirements) && in_array("f", $requirements)):
                return is_numeric($value) ? (float)$value : null;
            case $requirements == "f+" || (is_array($requirements) && in_array("f+", $requirements)):
                return is_numeric($value) ? ((float)$value >= 0 ? (float)$value : null) : null;
            case $requirements == "f-" || (is_array($requirements) && in_array("f-", $requirements)):
                return is_numeric($value) ? ((float)$value < 0 ? (float)$value : null) : null;
            case $requirements == "i" || (is_array($requirements) && in_array("i", $requirements)):
                return is_numeric($value) ? (int)$value : null;
            case $requirements == "i+" || (is_array($requirements) && in_array("i+", $requirements)):
                return is_numeric($value) ? ((int)$value >= 0 ? (int)$value : null) : null;
            case $requirements == "i-" || (is_array($requirements) && in_array("i-", $requirements)):
                return is_numeric($value) ? ((int)$value < 0 ? (int)$value : null) : null;
            case $requirements == "o" || (is_array($requirements) && in_array("o", $requirements)):
                return (object)$value;
            default:
                return $value;
        }
        return $value;
    }

    static function verifyRequirements(array $routeParams, string $path): RouteVariables|false
    {
        $routeVariables = new RouteVariables();
        foreach (explode("/", $routeParams["path"]) as $key => $subRoute) {
            if (strpos($subRoute, "{") !== false) {
                $routeVariables->setParams(substr($subRoute, 1, strpos($subRoute, "}") - 1), $key, $routeParams["requirements"][substr($subRoute, 1, strpos($subRoute, "}") - 1)] ?? null);
            }
        }
        
        foreach ($routeVariables->getName() as $key => $variableName) {
            $requirements = $routeVariables->getParams($variableName)["requirement"];
            $pos = $routeVariables->getParams($variableName)["pos"];
            $value = explode("/", $path)[$pos];

            $value = self::parseForRequirements($requirements, $value);
            if($value == null){
                return false;
            }
            $routeVariables->setValue($variableName, $value);
        }
        return $routeVariables;
    }


}