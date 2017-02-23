<?php


namespace MVC;


/**
 * Class Config
 *
 * @package MVC
 */
class Config
{
    /**
     * @var null
     * holds instance of this object
     */
    private static $_instance = null;
    /**
     * @var
     * holds config folder path
     */
    private $configFolderPath;
    /**
     * @var array
     * holds all config paths
     */
    private $configFolderFiles = [];


    /**
     * Config constructor.
     * its private one becouse its singleton
     */
    private function __construct()
    {
    }


    /**
     * @param string $configFolder
     * @throws \Exception
     */
    public function setConfigFolder(string $configFolder){
        if (!$configFolder) {
            throw new \Exception("ERROR: Config folder path is not valid path - {$configFolder}");
        }
        $configFolder = realpath($configFolder);
        if ($configFolder && is_dir($configFolder)) {
            if (!is_readable($configFolder)) {
                throw new \Exception("ERROR: Config folder path is not readable - {$configFolder} ");
            }
            $this->configFolderPath = $configFolder.DIRECTORY_SEPARATOR;
        }
    }
    
    public function includeConfigFile($pathToFile){
        if (!$pathToFile) {
            throw new \Exception("ERROR: Path not set - {$pathToFile}");  
        }

        $pathToFile = realpath($pathToFile);
        if ($pathToFile && is_file($pathToFile)) {
            if (!is_readable($pathToFile)) {
                throw new \Exception("ERROR: File is not readable - {$pathToFile}");
            }
            $fileName = explode(".php", basename($pathToFile))[0];

            $this->configFolderFiles[$fileName] = include $pathToFile;
        }
    }

    public function __get(string $name){
        // check if this array of data does not exist.. fetch it from the file
        if (!array_key_exists($name,$this->configFolderFiles)) {
            $this->includeConfigFile($this->configFolderPath.$name.".php");
        }
        // if data exist (fetch was successful) then return the data..
        if (array_key_exists($name , $this->configFolderFiles)) {
            return $this->configFolderFiles[$name];
        }

        return null; // return null because there might be a true/false as config
    }
    /**
     * @return Config
     */
    public static function getInstance():Config{
        if (self::$_instance == null) {
            self::$_instance = new Config();
        }
        return self::$_instance;
    }

}