<?php

namespace Kernel\Error;

// todo : creer une base d'exception qui accepte des parametre pour l'affichage a l'utilisateur et l'affichage perssonnaliser pour le dev
// il faut creer une nouvelle interface qui extends de \Exception puis y ajouter les methodes qui sont necessaire pour l'affichage

class BaseException extends \Exception{
    public function __construct($message = "", $code = 0, \Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}