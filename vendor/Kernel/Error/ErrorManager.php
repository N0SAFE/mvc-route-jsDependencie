<?php

namespace Kernel\Error;

use Kernel\Env\EnvManager;

class ErrorManager {
    public static function handleError(int $exceptionType, string $exceptionMessage, string $exceptionFile, int $exceptionLine, array $trace, \Exception $exceptionObj):void {
        (require __DIR__ . "/templates/" . '/error.vue.'. EnvManager::getTypeEnv().'.php')($exceptionType, $exceptionMessage, $exceptionFile, $exceptionLine, $trace, $exceptionObj);
    }
}