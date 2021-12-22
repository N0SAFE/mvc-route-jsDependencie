<?php

namespace Kernel\Ini;

use Build_in\Routing\Router;
use Kernel\Error\ErrorManager;
use Kernel\Env\EnvManager;

class IniManager {

    private static Router $actualRoute;
    // load all
    public static function start(){

        // load the autoload.php
        (require_once dirname(__DIR__) . '../../build-in/before/autoload/autoload.php')::load();

        // call global version like dump()
        require_once dirname(__DIR__)."/Function/GlobalsFunction.php";

        // Redirect to the subdir if the Router::getRoute() last element is /
        if(Router::getCurrentPath() != "/" && Router::getCurrentPath()[strlen(Router::getCurrentPath())-1] == "/") {
            Router::redirect("../".explode("/", Router::getCurrentPath())[count(explode("/", Router::getCurrentPath()))-2]);
        }

        // stock the env settings
        EnvManager::init();

        //! temp
        //! dump(EnvManager::getEnv(), "ENV");

        // start the router and stock it in
        IniManager::startRouter();
        
    }
    
    // launch the route manager
    public static function startRouter(){
        self::$actualRoute = new Router();
        // !dump(self::$actualRoute->getParams());
    }

}


try{
    IniManager::start();
} catch (\Exception $e) {
    ErrorManager::handleError($e->getCode(), $e->getMessage(), $e->getFile(), $e->getLine(), $e->getTrace(), $e);
}

// var_dump(IniManager::getAllVarEnv());
// var_dump(IniManager::getVarEnv("DATABASE_URL"));