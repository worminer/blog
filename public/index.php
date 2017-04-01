<?php
error_reporting(E_ALL & ~E_NOTICE);
require_once("." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "App" . DIRECTORY_SEPARATOR ."App.php");
$app = MVC\App::getInstance();
$app->run();
