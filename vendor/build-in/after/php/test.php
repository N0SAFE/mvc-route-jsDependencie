<?php

$content = trim(file_get_contents("php://input"));


var_dump(scandir("../../".$content));
