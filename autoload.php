<?php
// Autoload Classes, Controllers, Models, and Views
define("AUTOLOAD_ITEMS", array(CLASSES, CONTROLLERS, MODELS));        // add autoload items in the order they will be loaded
// auto loads controller/models/views when called
function AutoLoader($className) {
    foreach (AUTOLOAD_ITEMS as $itemPath){
        $filePath = $itemPath . DS . $className . ".php";
        if (file_exists($filePath)) {
            //echo $filePath.PHP_EOL;
            include_once($filePath);
        }
    }
}

spl_autoload_register('AutoLoader'); // we define our custom autoload function so we dont need to use the __autoload()