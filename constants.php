<?php
//TODO: Maybe refactor all the variables to be in the ConfigClass ...
//Aliases
define("DIR",__DIR__); // alias for __DIR__
define("ROOT",__DIR__); // project ROOT directory
define("DS",DIRECTORY_SEPARATOR); // Alias for DIRECTORY_SEPARATOR because.. Why not use german word instead

// Site Constants

define("PUBLIC_DIR",  DIR.DS."public"); // Public directory

//Autoload config
define("CLASSES",     ROOT.DS."classes");   // controllers folder
define("CONTROLLERS", ROOT.DS."controllers");   // controllers folder
define("MODELS",      ROOT.DS."models");        // models folder


//Templating Constants

define("VIEWS",       ROOT.DS."views");         // views folder
define("PARTIALS",    VIEWS.DS."partials");         // views folder
define("DEFAULT_LAYOUT", "layout");
define( "TEMPLATE_EXT", ".phtml"); // extension for the views .. i dont know a good one..

// this body tag snows to View controller where to put the the current view
define("TEMPLATE_BODY_TAG", "{%body%}");

// This tag shows to View controller where to replace all the variables given to him
// %VAR_NAME% will be replaced with variable name, so if you edit it you have to put %VAR_NAME%..
define("TEMPLATE_VARIABLE", "{#%VAR_NAME%}");

// This tag shows to View controller where to replace all the variables given to him
// %VAR_NAME% will be replaced with variable name, so if you edit it you have to put %VAR_NAME%..
define("TEMPLATE_GLOBAL_VARIABLE", "{@%VAR_NAME%}");

// This tag shows to View controller where to replace all the Partials in the file
// %VAR_NAME% will be replaced with variable name, so if you edit it you have to put %VAR_NAME%..
define("TEMPLATE_PARTIAL", "{>%PARTIAL_NAME%}");

