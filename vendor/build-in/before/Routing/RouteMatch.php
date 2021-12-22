<?php

namespace Build_in\Routing;


class RouteMatch {
    private static function subPathIsValid($subPath){
        foreach($subPath as $item){
            if(!(strpos($item, "{") !== false) xor !(strpos($item, "}") !== false) || (count(explode("{", $item)) > 2 || count(explode("}", $item)) > 2)){
                return false;
            }
        }
        return true;
    }

    // verify all the route in dev mode
    public static function isValidRoute(string $path){
        // verification : path must start with '/'
        // verification : path must not end with '/'
        // verification : path must not contain '//'
        // verification : path must not contain '..'
        // verification : path must not contain '.'
        // verification : path must not contain '~'
        // verification : path must not contain '`'
        // verification : path must not contain '$'
        // verification : path must not contain '#'
        // verification : path must not contain '%'
        // verification : path must not contain '^'
        // verification : path must not contain '&'
        // verification : path must not contain '*'
        // verification : path must not contain '('
        // verification : path must not contain ')'
        // verification : path must not contain '['
        // verification : path must not contain ']'
        // verification : path can contain '{'
        // verification : path can contain '}'
        // verification : path must not contain ':'
        // verification : path must not contain ';'
        // verification : path must not contain '?'
        // verification : path must not contain '='
        // verification : path must not contain '+'
        // verification : path must not contain ','
        // verification : path must not contain '"'
        // verification : path must not contain ' '
        // verification : path can't countain '{' without '}' and vice versa
        // verification : the pos of '{' must be the first and the pos of '}' must be the last
        
        $path = ltrim($path);
        $path = rtrim($path);
        if(strpos($path, " ") !== false){
            return false;
        }
        if(strlen($path) == 0){
            return false;
        }
        if($path[0] != '/'){
            return false;
        }
        if($path[strlen($path)-1] == '/'){
            return false;
        }
        if(strpos($path, "//") !== false){
            return false;
        }
        if(strpos($path, "..") !== false){
            return false;
        }
        if(strpos($path, ".") !== false){
            return false;
        }
        if(strpos($path, "~") !== false){
            return false;
        }
        if(strpos($path, "`") !== false){
            return false;
        }
        if(strpos($path, "$") !== false){
            return false;
        }
        if(strpos($path, "#") !== false){
            return false;
        }
        if(strpos($path, "%") !== false){
            return false;
        }
        if(strpos($path, "^") !== false){
            return false;
        }
        if(strpos($path, "&") !== false){
            return false;
        }
        if(strpos($path, "*") !== false){
            return false;
        }
        if(strpos($path, "(") !== false){
            return false;
        }
        if(strpos($path, ")") !== false){
            return false;
        }
        if(strpos($path, "[") !== false){
            return false;
        }
        if(strpos($path, "]") !== false){
            return false;
        }
        if(strpos($path, ":") !== false){
            return false;
        }
        if(strpos($path, ";") !== false){
            return false;
        }
        if(strpos($path, "?") !== false){
            return false;
        }
        if(strpos($path, "=") !== false){
            return false;
        }
        if(strpos($path, "+") !== false){
            return false;
        }
        if(strpos($path, ",") !== false){
            return false;
        }
        if(strpos($path, '"') !== false){
            return false;
        }
        // verification for subPath = explode("/", $path);
        $subPath = explode("/", $path);
        if(!RouteMatch::subPathIsValid($subPath)){
            return false;
        }
        return true;
    }

    public static function createRouteVariables($route, $requirements = []):RouteVariables{
        $PathVariable = new RouteVariables();

        foreach(explode("/", $route) as $key => $item){
            if(strpos($item, "{") !== false){
                $PathVariable->setParams(str_replace("{", "", str_replace("}", "", $item)), $key-1, $requirements[str_replace("{", "", str_replace("}", "", $item))] ?? null);
            }
        }
        
        return $PathVariable;
    }
}