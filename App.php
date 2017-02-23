<?php
namespace MVC;
require_once ("constants.php");
require_once("AutoLoader.php");


class App {
    /**
     * we will use singleton style logic because we will need only one instance of App..
     * so this will hold the instance of App
     * @var App
     */
    private static $_instance = null;


    private $config; // depricated
    /**
     * App constructor.
     */
    private function __construct()
    {

        // registering namespace MVC
        AutoLoader::registerNamespace("MVC", dirname(__FILE__));

        // register the autoload function that will be called when file is needed..
        AutoLoader::registerAutoLoad();
    }

    /**
     * returns the current instance of app.. or if there is not any .. it will create new instance and return it !
     * @return App
     */
    public static function getInstance():App{
        if (self::$_instance == null) {
            self::$_instance = new App();
        }
        return self::$_instance;
    }

    public function run(){

       // new \MVC\AutoLoader();
//        //TODO: proper Routing..
//        try{
//            new HomeController("index", $this->getUrlParams());
//        }catch (Exception $e) {
//            if (SHOW_EXCEPTIONS) {
//                echo $e->getMessage();
//            }
//        }


    }
    //depricated
//    // This have to be in the routing logic!
//    public function getUrlParams():array {
//        $urlRequest = array_filter(explode("/", trim($_SERVER['REQUEST_URI'])));
//        $urlParams = array_diff($urlRequest,$this->config->createProjectRootArr()); //remove Project Root from URL params so we get the Request Parameters
//        return $urlParams;
//    }

}