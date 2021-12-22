<?php

return function(int $exceptionType, string $exceptionMessage, string $exceptionFile, int $exceptionLine, array $trace, \Exception $exceptionObj){
    echo '
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
    </head>
    <body style="margin: 0; background: red; width: 100vw; height: 100vh;">
        '.

        
        var_dump($exceptionMessage)
        
        .dump($trace, "Trace", 1)

        .'
    </body>
    </html>
    ';
};