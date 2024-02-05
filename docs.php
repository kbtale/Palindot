<?php
require ('vendor/autoload.php');
$openapi = \OpenApi\Generator::scan('/app');
header('Content-Type: application/json');
echo $openapi->toJson();
