<?php
//Aliases
define("DIR",__DIR__); // alias for __DIR__
define("ROOT",__DIR__); // project ROOT directory
define("DS",DIRECTORY_SEPARATOR); // Alias for DIRECTORY_SEPARATOR because.. Why not use german word instead

// Site Constants

define("PUBLIC_DIR",  DIR.DS."public"); // Public directory
define("CONFIG",      ROOT.DS."config");        // config folder
define("APP_CONFIG",  CONFIG.DS."appConfig.php"); // path to config

define("VIEWS",       ROOT.DS."views");         // views folder
define("DEFOULT_LAYOUT", "layout");
define( "TEMPLATE_EXT", ".html"); // extension for the views .. i dont know a good one..

//Autoload config
define("CLASSES",     ROOT.DS."classes");   // controllers folder
define("CONTROLLERS", ROOT.DS."controllers");   // controllers folder
define("MODELS",      ROOT.DS."models");        // models folder

