<?php
require_once("." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "App.php");



$app = MVC\App::getInstance();
$config = \MVC\Config::getInstance();
$config->setConfigFolder("../config/");
echo $config->app["test"];
$app->run();

