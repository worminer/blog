<?php
namespace MVC;

require_once("AutoLoader.php");


class App {
    /**
     * will use singleton style logic because we will need only one instance of App..
     * so this will hold the instance of App
     * @var App
     */
    private static $_instance = null;

    /**
     * @var Config
     * will hold instance of config
     */
    private $_config = null;

    /**
     * @var FrontController
     * will hold the FrontController instance.
     */
    private $_frontController = null;
    /**
     * App constructor.
     */


    private function __construct()
    {
        // registering namespace MVC
        AutoLoader::registerNamespace("MVC", dirname(__FILE__));
        // register the autoload function that will be called when file is needed..
        AutoLoader::registerAutoLoad();
        //get instance of config
        $this->_config = Config::getInstance();
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
        // if there is no config folder set..sets the default one
        if ($this->_config->getConfigFolderPath() == null) {
            $this->_config->setConfigFolder("../config");
        }

        //have to get an instance of the FrontController
        $this->_frontController = FrontController::getInstance();

        // setting default router
        if (isset($this->_config->routers["DefaultRouter"])) {
            $defaultRouter = $this->_config->routers["DefaultRouter"];
            $this->_frontController->setRouterSettings($defaultRouter);
        } else {
            throw new \Exception("ERROR: Default Router is not defined at Routers config");
        }
        // and we dispatch ..
        $this->_frontController->dispatcher();
    }
    /**
     * @return Config
     */
    public function getConfig(): Config
    {
        return $this->_config;
    }

    //ROUTER FUNCTIONALITY

    public function setRouter(string $routerName) {
        $this->_frontController->setRouterSettings([$routerName]);
    }

    public static function render(string $view, array $data, $layout = null){
        new View($view,$data,$layout);
    }

}