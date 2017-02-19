<?php
require_once ("constants.php");
require_once ("autoload.php");

class app {

    private $config;
    /**
     * app constructor.
     */
    public function __construct()
    {
        $this->config = new Config(); // initialize config!
    }

    public function start(){
        if (DEBUG_MVC) {
            echo "<hr>I am running<br>".PHP_EOL;
        }

        //TODO: proper Routing..
        try{
            new HomeController("index", $this->getUrlParams());
        }catch (Exception $e) {
            if (SHOW_EXCEPTIONS) {
                echo $e->getMessage();
            }
        }


    }
    // This have to be in the routing logic!
    public function getUrlParams():array {
        $urlRequest = array_filter(explode("/", trim($_SERVER['REQUEST_URI'])));
        $urlParams = array_diff($urlRequest,$this->config->createProjectRootArr()); // we remove Project Root from URL params so we get the Request Parameters
        return $urlParams;
    }

}