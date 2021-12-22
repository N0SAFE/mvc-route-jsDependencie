<?php

// call the function $settingsName = a good name or nothing
// var_dump(getSettings("database"));
// return is an array of params
// this function is used to get all settings and select between the local settings or the global settings for the app


function getSettings($settingName = ""){
    $settingsPath = json_decode(file_get_contents("pathSettings.json"), true);
    if(!empty($settingName)){
        if(isset($settingsPath[$settingName])){
            if(isset($settingsPath[$settingName]["local"]) && file_exists($settingsPath[$settingName]["local"])){
                return json_decode(file_get_contents($settingsPath[$settingName]["local"]), true);
            }
                return json_decode(file_get_contents($settingsPath[$settingName]["global"]), true);
        }
        return null;
    }else{
        $sort = [];
        foreach ($settingsPath as $key => $value) {
            if(isset($value["local"]) && file_exists($value["local"])){
                $sort[$key] = json_decode(file_get_contents($value["local"]), true);
            }else{
                $sort[$key] = json_decode(file_get_contents($value["global"]), true);
            }
        }
        return $sort;
    }
}