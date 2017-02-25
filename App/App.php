<?php
namespace MVC;

use MVC\Session\SessionInterface;

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
     * @var array
     * will hold database connections
     */
    private $_DbConnections = [];
    /**
     * @var SessionInterface
     * will hold session obj
     */
    private $_session = null;

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

    /**
     * @throws \Exception
     */
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

        //session settings
        $sessionConfig = $this->_config->app['session'];
        if ($sessionConfig["autostart"]) {
            if ($sessionConfig["type"] == "native") {
                echo "i am here";
                $this->_session  = new Session\NativeSession($sessionConfig["name"],$sessionConfig["lifetime"],$sessionConfig["path"],$sessionConfig["domain"], $sessionConfig["secure"]);
            }
        }

        // and we dispatch ..
        $this->_frontController->dispatcher();
    }
    /**
     * @param string $view
     * @param array $data
     * @param null $layout
     */
    public static function render(string $view, array $data, $layout = null){
        new View($view,$data,$layout);
    }

    public function setSession(Session\SessionInterface $session){
        $this->_session = $session;
    }
    public function getSession(){
        return $this->_session;
    }


    /**
     * @param string $connectionName
     * @return \PDO
     * @throws \Exception
     */
    public function getDbConnection(string $connectionName = "default"){
        //check if connection name is not false,empty null or whatever
        if (!$connectionName) {
            throw new \Exception("ERROR DB: Connection name is not set!",500);
        }
        // check if the connection is not cached
        if (isset($this->_DbConnections[$connectionName])) {
            return $this->_DbConnections[$connectionName];
        }
         // check get the config file for database
        $dbConfigs = $this->_config->database;
        // check if there is a config file for this connectrion
        if (!isset($dbConfigs[$connectionName])) {
            throw new \Exception("ERROR DB: There is no such configuration in the database config file",500);
        }
        //get the connection settings
        $dbConfig = $dbConfigs[$connectionName];
        //create new PDO connection
        $currentDBConn = new \PDO($dbConfig["connection_url"],
            $dbConfig["username"],
            $dbConfig["password"],
            $dbConfig["pdo_options"]);
        //Cache the connection for future use
        $this->_DbConnections[$connectionName] = $currentDBConn;
        // return the connection
        return $currentDBConn;
    }
    /**
     * @return Config
     */
    public function getConfig(): Config
    {
        return $this->_config;
    }

    //ROUTER FUNCTIONALITY

    /**
     * @param string $routerName
     */
    public function setRouter(string $routerName) {
        $this->_frontController->setRouterSettings([$routerName]);
    }


}