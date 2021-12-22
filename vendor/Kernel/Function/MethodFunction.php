<?php

namespace Kernel\Function;

class MethodFunction {
    static public function __method_get_properties($class, $method, $strval = false)
    {
        $attribute_names = [];
        if (method_exists($class, $method)) {
            $fx = new \ReflectionMethod($class, $method);
            foreach ($fx->getParameters() as $param) {
                $attribute_names[] = ["name" => $param->getName(), "type" => $strval == true ? strval($param->getType()) : $param->getType(), "default_value" => $param->isDefaultValueAvailable() ? $param->getDefaultValue() : null];
            }
        }

        return $attribute_names;
    }

    static public function __function_get_properties($funcName)
    {
        $attribute_names = [];
        if (function_exists($funcName)) {
            $fx = new \ReflectionFunction($funcName);
            foreach ($fx->getParameters() as $param) {
                $attribute_names[] = ["name" => $param->getName(), "type" => $param->getType(), "default_value" => $param->isDefaultValueAvailable() ? $param->getDefaultValue() : null];
            }
        }

        return $attribute_names;
    }
}