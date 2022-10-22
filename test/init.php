<?php

require_once dirname(__DIR__) . "/vendor/autoload.php";
require_once "BaseTest.phpt";

\Tester\Environment::setup();

if(!file_exists(__DIR__ . "/auth.ini")){
    exit("File auth.init not found");
}

$initFile = parse_ini_file("auth.ini");
$clientId = $initFile["clientId"] ?? null;
if(!$clientId){
    exit("clientId in auth.ini not found");
}
$password = $initFile["password"] ?? null;
if(!$password){
    exit("password in auth.ini not found");
}