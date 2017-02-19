<?php
define("DEBUG_MVC",false); // debug mode for MVC

define("SHOW_EXCEPTIONS",true);
class Config {
    // default site root path from the root directory
    // will be set later
    private static $config = [];

    public function __construct()
    {
        self::setConfig("projectRoot",    $this->createProjectRootStr());
        self::setConfig("resourcesFolder", self::getConfig("projectRoot")."/resources");

    }

    public static function setConfig(string $key, $value) {

        self::$config[strtolower($key)] = $value;
    }

    public static function getConfig($key) {
        $key = strtolower($key);
        if (isset(self::$config[$key])) {
            return self::$config[$key];
        }
        return null;
    }

    public static function listConfig() {
        return self::$config;
    }


    public function createProjectRootArr():array {
        $filePosition = explode( DIRECTORY_SEPARATOR, trim(PUBLIC_DIR));
        $documentRoot = explode("/", trim($_SERVER['DOCUMENT_ROOT']));
        $projectRoot = array_diff($filePosition ,$documentRoot ); // removing Document Root form File Root so we get only the Project Root
        return $projectRoot;
    }

    private function createProjectRootStr():string {
        return implode("/",$this->createProjectRootArr());

    }

}


