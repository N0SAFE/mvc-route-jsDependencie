<?php

namespace Build_in\Routing;

use Kernel\Error\Route\RouteException;

class Router {

    // cutome regex : +d : digit, +w : only word (no int), +c : one character, +a : all character, (+t=50~10 : token=50characters~10characters{40-60}, +t=50~+10 : token=50characters~+10characters{50-60}, +t=50~-10 : token=50characters~-10characters{40-50}, +t=50~+i : token=50characters~+infinity{50-infinity}, +t=50~-i : token=50characters~-infinity{0-50})
    // error : custom regex : +d+w+c+a+t=50 : digit+word+character+all+token=50characters
    public function __construct()
    {
        $GLOBALS["route"] = array();

        //! dump(self::getCurrentPath(), "getCurrentPath");

        $params = Params::getRoute(Router::getCurrentPath());
        if(!$params) throw new RouteException(Router::getCurrentPath());

        $this->params = $params;

        //! dump($params, "finishRouteParams");
        
        $controllerSplit = explode("::", $params["routeParams"]["controller"]);
        if(count($controllerSplit) != 2) throw new RouteException("the controller name is not valid");
        $controllerName = "\\".$controllerSplit[0];
        $controllerAction = $controllerSplit[1];
        $controllerObject = new $controllerName();

        if (!class_exists($controllerName)) throw new RouteException("The controller name is not valid");
        if (!method_exists($controllerObject, $controllerAction)) throw new RouteException("The controller action is not valid");

        if (!is_callable([$controllerObject, $controllerAction])) throw new RouteException("The controller action is not valid");


        $orderedArrayParams = Params::organizeControllerParams($controllerName, $controllerAction, $params["var"]);

        $GLOBALS["route"] = array(
            "actualPath" => self::getCurrentPath(),
            "actualRoute" => $this->params["routeParams"]["path"],
            "params" => $this->params,
            "controller" => array(
                "name" => $controllerName,
                "action" => $controllerAction,
                "object" => $controllerObject,
                "orderedArrayParams" => $orderedArrayParams
            )
        );
        
        $controllerObject->$controllerAction(...$orderedArrayParams);

    }

    public function getParams(){
        return $this->params;
    }

    public static function getCurrentPath()
    {
        if (!isset($_SERVER['REDIRECT_VAR1'])) {
            $routeName = "";
        } else {
            $routeName = substr($_SERVER['REQUEST_URI'], strlen(substr(dirname($_SERVER['REDIRECT_VAR1']), strlen($_SERVER["DOCUMENT_ROOT"]), strlen(dirname($_SERVER['REDIRECT_VAR1'])))) + 1);
        }
        return "/" . explode("?", $routeName)[0];
    }

    public static function redirect($url) {
        header("Location: $url");
        exit();
    }

    private function mergeBaseArray($array):array{
        $baseArrayValue = array(
            "path" => "",
            "method" => "GET",
            "controller" => "",
            "action" => "",
            "params" => []
        );
        $array["value"] = array_merge($baseArrayValue, $array["value"]);
        return $array;
    }
}