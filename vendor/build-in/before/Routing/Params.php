<?php

namespace Build_in\Routing;

use Kernel\Error\Route\RouteParamsException;
use Kernel\Function\MethodFunction;
use Kernel\Error\Var\ParameterException;

class Params {
    static public function organizeControllerParams(string $controllerName, string $actionName, RouteVariables $pathVariables):array
    {
        $controllerVar = MethodFunction::__method_get_properties($controllerName, $actionName, true);
        
        //! dump($pathVariables->getAll(), "pathVariables");
        //! dump($controllerVar, "controllerVar");

        $sortedArray = [];

        foreach($controllerVar as $key => $value){
            $var = ["name" => $value["name"], "send" => $pathVariables->getParams($value["name"])];
            if($var["send"] === null){
                throw new ParameterException("the argument ". $value["name"] ." is called by the controller but not exist in the route params or built_in params");
            }
            $type = ["send" => get_debug_type($var["send"]["value"]), "require" => $value["type"]];
            
            if($type["send"] != $type["require"]){
                throw new RouteParamsException("the argument ". $value["name"] ." is called by the controller but the type not matched");
            }
            $sortedArray[] = $var["send"]["value"];

        }
        return $sortedArray;
    }


    // return : ["name" => "","path" => "", "method" => "", "controller" => "", "requirements" => []]
    static public function getRoute(string $urlPath):array|null
    {
        $routeFile = "/config/route.yaml";

        // routes is the array of all the routes
        $routes = yaml_parse_file($GLOBALS["dirPath"]["baseDir"] . $routeFile);

        //! dump($routes, "routes");


        // function to get all the possibleRoute (if the path is /test/mathis)
        // the route can be /test/{name}(requirement name: d+) or /test/{name}(no requirement) or /test/mathis

        $possibleRoutes = (function () use ($urlPath, $routes) {

            // function inner
            $subRouteIsVar = function ($subRoute) {
                if (strpos($subRoute, "{") !== false
                ) {
                    return true;
                }
                return false;
            };

            // filtre toute les route qui ne sont pas de la bonne forme             
            $result = array_filter($routes, function ($routeParams) use ($urlPath, $subRouteIsVar) {
                if ($routeParams["path"] == $urlPath) {
                    return true;
                } else if (count(explode("/", $routeParams["path"])) == count(explode("/", $urlPath))) {
                    return array_every(explode("/", $routeParams["path"]), function ($index, $subRoute) use ($urlPath, $subRouteIsVar) {
                        if ($subRouteIsVar($subRoute)) {
                            return true;
                        } else {
                            return $subRoute == explode("/", $urlPath)[$index];
                        }
                    }, ARRAY_FILTER_USE_BOTH);
                }
            });

            foreach($result as $key => $value){
                $result[$key]["name"] = $key;
            }

            return $result;
        })();

        //! dump($possibleRoutes, "possibleRoutes");



        if (count($possibleRoutes) == 0) {
            return null;
        } else if (count($possibleRoutes) == 1) {
            $ret = Requirement::verifyRequirements($possibleRoutes[array_keys($possibleRoutes)[0]], $urlPath);
            if($ret === false){
                return null;
            }
            return ["var" => $ret, "routeParams" => $possibleRoutes[array_keys($possibleRoutes)[0]], "path"=>$urlPath];
        } else {

            if ($result = array_search($urlPath, array_column($possibleRoutes, "path")) !== false) {
                return ["routeParams"=>$possibleRoutes[array_keys($possibleRoutes)[$result - 1]], "var"=>null, "path" => $urlPath];
            }

            foreach ($possibleRoutes as $routeName => $routeParams) {

                if($params = Requirement::verifyRequirements($possibleRoutes[array_keys($possibleRoutes)[0]], $urlPath) !== false){
                    return ["var"=>$params, "routeParams"=>$possibleRoutes[array_keys($possibleRoutes)[0]], "path" => $urlPath];
                }
            }
        }
        return null;
    }
}