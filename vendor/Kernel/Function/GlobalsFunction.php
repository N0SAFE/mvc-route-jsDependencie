<?php

function dump(mixed $all, string $name = "", $template = 0) {
    switch($template){
        case 0:
            echo "<pre style='background: black; color: white; padding: 15px; margin: 0;'>";
            if ($name != ""
            ) {
                echo "<h3 style='color:red; text-align: center;'>$name</h3>";
            }
            var_dump($all);
            echo "</pre>";
            break;
        case 1:
            echo "<pre style='color:white;padding: 15px; margin: 0;'>";
            if ($name != ""
            ) {
                echo "<h3 style='color:white; text-align: center;'>$name</h3>";
            }
            var_dump($all);
            echo "</pre>";
            break;
    }


};


function array_some(array $array, callable $fn, int $flag = 0) {
    foreach ($array as $key => $value) {
        if($flag == 1){
            if ($fn($key, $value)) {
                return true;
            }
        }else if($flag == 2){
            if ($fn($key)) {
                return true;
            }
        }else{
            if($fn($value)) {
                return true;
            }
        }
    }
    return false;
}

function array_every(array $array, callable $fn, int $flag = 0) {
    foreach ($array as $key => $value) {
        if($flag == 1){
            if(!$fn($key, $value)) {
                return false;
            }
        }else if($flag == 2){
            if(!$fn($key)) {
                return false;
            }
        }else{
            if(!$fn($value)) {
                return false;
            }
        }
    }
    return true;
}