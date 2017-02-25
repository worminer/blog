<?php
//Aliases and constants
define("DIR",dirname(__FILE__)); // alias for __DIR__

define("DS",DIRECTORY_SEPARATOR); // Alias for DIRECTORY_SEPARATOR because.. Why not use german word instead
define("ROOT",realpath("../")); // project ROOT directory

$config["root_dir"] = realpath("../"); // root dir path

$config["public_dir"] = str_replace('/','\\', str_replace("index.php", '', $_SERVER['SCRIPT_NAME'])); // public dir path.

$config["resources_folder"] = $config["public_dir"].DS.'resources';

$config["site_title"] = "Team Darby Blog"; // site title


/**NAMESPACE OPTIONS!**/
/**
 * this configuration is for autoloading namespaces
 * they have to be a $key => $value pair as "namespace" => "path_to_folder"
 * to start autoloading it have to have at least one key=>value pair
 */
$config["namespaces"] = [
    "Controllers" => $config["root_dir"].DS.'controllers'
];

/** SESSION CONFIGURATION **/
$config["session"] = [
    "autostart" => false,
    "type"      => "native",
    "name"      => "_sess",
    "lifetime"  => 3600,
    "path"      => "/",
    "domain"    => "",
    "secure"    => false,
];

return $config;