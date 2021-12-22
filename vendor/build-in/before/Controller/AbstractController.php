<?php

namespace Build_in\Controller;


class AbstractController {
    static function render(string $path, array $args):mixed {
        extract($args);

        // this var is create for not override the var extracted of $args
        $env124578963 = array();
        $env124578963["actualUrlPath"] = $GLOBALS["route"]["actualPath"];

        $up = "";
        for ($i = 0; $i < count(explode("/", $env124578963["actualUrlPath"])) - 2; $i++) {
            $up .= "../";
        }
        $after = $up . "vendor/build-in/after/";
        self::createWindowVarJs($after);
        $up.="public/".dirname($path)."/";

        $env124578963["path"] = $GLOBALS["dirPath"]["baseDir"] . "/public/" . $path;
        if(file_exists($env124578963["path"])){
            return require_once $env124578963["path"];
        }
        return null;
    }
    static private function createWindowVarJs(string $afterPath){
        echo "<script type='module' ns-id='scpt-auto-destroy'>import setBuild from './".$afterPath."ini/ini.js'; window.setBuild = setBuild;</script>";

        $temp = "/";
        for($i=0 ; $i< substr_count($GLOBALS["route"]["actualPath"], "/") ; $i++){
            $temp.="../";
        }
        
        echo "<script type='module' ns-id='scpt-auto-destroy'>window.baseURL = new URL(document.baseURI+'$temp').href</script>";

        // echo "<script ns-id='scpt-auto-destroy'>Array.from(document.querySelectorAll('[ns-id=scpt-auto-destroy]')).forEach(element => {element.remove()});</script>";
    }
    static function getSession($key):mixed{
        return $_SESSION[$key] ?? null;
    }
    static function setSession($key,$value, bool $safe = false){
        if(!isset($_SESSION)){
            session_start();
        }
        if($safe){
            if(!isset($_SESSION[$key])){
                $_SESSION[$key] = $value;
            }
        }else{
            $_SESSION[$key] = $value;
        }
    }

}