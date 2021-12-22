<?php

namespace Build_in\Routing;

class RouteVariables {
    private array $var = array();
    public function __construct(){}

    public function setPath(string $path):void{
        $this->path = $path;
    }
    public function setParams(string $VariableName, int $pos, null|string|array $requirement = null):void{
        $this->var[$VariableName] = ["pos"=>$pos, "requirement"=>$requirement];
    }
    public function getName():array{
        return array_keys($this->var);
    }
    public function getParams($variableName):array|null{
        return $this->var[$variableName] ?? null;
    }
    public function getAll():array{
        $ret = [];
        foreach($this->var as $key => $value){
            $value["name"] = $key;
            $ret[] = $value;
        }
        return $ret;
    }
    public function setValue($variableName, $value):bool{
        if(!isset($this->var[$variableName])){
            return false;
        }
        $this->var[$variableName]["value"] = $value;
        return true;
    }
}