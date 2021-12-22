<?php

$build_in = dirname(dirname(__FILE__));
$vendorDir = dirname(dirname($build_in));
$baseDir = dirname($vendorDir);

$GLOBALS["dirPath"] = array(
    "baseDir" => $baseDir,
    "vendorDir" => $vendorDir,
    "build_in" => $build_in,
    "templates" => $baseDir . "/templates",
    "public" => $baseDir . "/public",
    "src" => $baseDir . "/src"
);