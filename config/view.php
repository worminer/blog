<?php
//Templating Constants
$viewConfig["VIEW_FOLDER"]  = ROOT.DS."views";      // views folder

$viewConfig["PARTIALS_FOLDER"]     = $viewConfig["VIEW_FOLDER"].DS."partials";  // views folder

$viewConfig["DEFAULT_LAYOUT"] = "layout";           // default layout

$viewConfig["TEMPLATE_EXT"] = ".phtml";             // extension for the views .. i don't know a good one.. but this one alows for using html and php syntax

$viewConfig["TEMPLATE_BODY_TAG"] = "{%body%}";      // this tag shows where the view will be inserted

// This tag shows to View controller where to replace all the variables given to him
// %VAR_NAME% will be replaced with variable name, so if you edit it you have to put %VAR_NAME%..
$viewConfig["TEMPLATE_VARIABLE"] = "{#%VAR_NAME%}";

// This tag shows to View controller where to replace all the variables given to him
// %VAR_NAME% will be replaced with variable name, so if you edit it you have to put %VAR_NAME%..
$viewConfig["TEMPLATE_GLOBAL_VARIABLE"] = "{@%VAR_NAME%}";

// This tag shows to View controller where to replace all the Partials in the file
// %VAR_NAME% will be replaced with variable name, so if you edit it you have to put %VAR_NAME%..
$viewConfig["TEMPLATE_PARTIAL"] = "{>%PARTIAL_NAME%}";

$viewConfig["IF_BLOCK_START"]   = '(?:{{ *)(#if)(?: *)(\w+)(?: *}})';  // matches "if" and  "author" from {{# if author }} {{#if author}}
$viewConfig["IF_BLOCK_END"]     = '(?:{{ *)(\/if)(?: *}})';             // matches "/if" from {{/if}} or {{ /if }}
$viewConfig["IF_BLOCK_ELSE"]    = '(?:{{ *)(else)(?: *}})';             // matches  "else" from {{ else }} or {{else}}
//$viewConfig[""] = "";
//$viewConfig[""] = "";

return $viewConfig;