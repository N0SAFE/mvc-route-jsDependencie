<?php

namespace Kernel\Env;


class EnvManager {
    static private array $env = [];
    static private string $env_base_file = '.env';
    static private string $env_local_file = '.env.local';
    static private string $env_dev_file = '.env.dev';
    static private string $env_prod_file = '.env.prod';
    static private string $env_test_file = '.env.test';
    static private string $envDir = "";
    static private string $envType = "";
    static private string $envPhpVersion = "";

    static function init() {
        self::$envDir = $GLOBALS["dirPath"]["baseDir"];
        self::$env = self::getEnv();
        self::$envType = self::getTypeEnv();
        self::$env = self::getEnvWithType();
        self::$envPhpVersion = phpversion();
        $GLOBALS["env"] = array("env" => self::$env, "type" => self::$envType, "php_version" => self::$envPhpVersion);
    }

    static public function getPhpVersion(){
        return self::$envPhpVersion;
    }

    static function getEnvWithType(){
        $typed_files = [
            "dev" => self::$env_dev_file,
            "prod" => self::$env_prod_file,
            "test" => self::$env_test_file
        ];
        return array_merge(file_exists($typed_files[self::$envType]) ? parse_ini_file($typed_files[self::$envType]) : [], self::$env);
    }

    static function getTypeEnv(){
        if(self::$envType == ""){
            switch(self::$env["APP_ENV"] ?? null){
                case "dev":
                    return "dev";
                case "prod":
                    return "prod";
                case "test":
                    return "test";
                default:
                    return "prod";
            }
        }
        return self::$envType;
    }

    static function getEnv() {
        $env = [];
        $env_files = [
            self::$env_base_file,
            self::$env_local_file,
        ];
        foreach ($env_files as $env_file) {
            $env_file_path = self::$envDir . '/' . $env_file;
            if (file_exists($env_file_path)) {
                $env = array_merge($env, parse_ini_file($env_file_path));
            }
        }
        return $env;
    }

    static function get(string $key) {
        return self::$env[$key] ?? null;
    }

    static function set(string $key, $value) {
        self::$env[$key] = $value;
    }

    static function getAll() {
        return self::$env;
    }

    static function setAll(array $env) {
        self::$env = $env;
    }

    // save the modify env in self::$env
    static function save() {
        $env_files = [
            self::$env_base_file,
            self::$env_local_file,
            self::$env_dev_file,
            self::$env_prod_file,
            self::$env_test_file,
        ];

        foreach ($env_files as $env_file) {
            $env_file_path = self::$envDir . '/' . $env_file;
            if (file_exists($env_file_path)) {
                unlink($env_file_path);
            }
        }

        foreach (self::$env as $key => $value) {
            $env_file_path = self::$envDir . '/' . $key . '.env';
            if (!file_exists($env_file_path)) {
                file_put_contents($env_file_path, $key . '=' . $value . PHP_EOL);
            }
        }
    }

    static function getEnvDir() {
        return self::$envDir;
    }

    static function setEnvDir(string $envDir) {
        self::$envDir = $envDir;
    }

    static function getEnvFile(string $env) {
        switch ($env) {
            case 'base':
                return self::$env_base_file;
            case 'local':
                return self::$env_local_file;
            case 'dev':
                return self::$env_dev_file;
            case 'prod':
                return self::$env_prod_file;
            case 'test':
                return self::$env_test_file;
            default:
                return null;
        }
    }

    static function getEnvFilePath(string $env) {
        return self::$envDir . '/' . self::getEnvFile($env);
    }

    static function getEnvFileContent(string $env) {
        $env_file_path = self::getEnvFilePath($env);
        if (file_exists($env_file_path)) {
            return parse_ini_file($env_file_path);
        }
        return [];
    }
}