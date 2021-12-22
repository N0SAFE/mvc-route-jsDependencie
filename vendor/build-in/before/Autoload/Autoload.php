<?php

class Autoload {
    public static function load(){
        require "baseDir.php";
        // make a php autoloader 
        spl_autoload_register(function(string $class):void {
            
            // echo "<pre>";
            // echo "class: ".$class."\n";
            // echo "baseDir: ".$GLOBALS["baseDir"]."\n";
            // echo "</pre>";

            $class = str_replace('\\', '/', $class);
            // remove first characters if it's a slash
            $class = ltrim($class, '/');
            // verify if first word is App and replace it by src with substr
            if(strpos($class, 'App') === 0) {
                $class = "src".substr($class, 3);
            }else if(strpos($class, 'Build_in')===0){
                $class = "vendor/build-in/before".substr($class, 8);
            }else if(strpos($class, 'vendor')!==0){
                $class = "vendor/".$class;
            }
            $file = '/'. "../../../../" . $class . '.php';
            $file = __DIR__.$file;
            $file = (function (string $file){
                foreach(explode("/", $file) as $value){
                    if(strpos($value, "int") === 0 && is_numeric(substr($value, 3))){
                        $file = str_replace($value, substr($value, 3), $file);
                    }
                }
                return $file;
            })($file);
            if (file_exists($file)) {
                try{
                    require_once $file;
                }catch(Exception $e){
                    echo $e->getMessage();
                }
                
            }
        });
    }
}

return Autoload::class;