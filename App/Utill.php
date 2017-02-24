<?php


namespace MVC;


class Utill
{
    public static function normalize($data,string $types){
        $types = explode("|", $types);
        if (is_array($types)) {
            foreach ($types as $type){
                if ($type == 'int') {
                    $data = (int) $data;
                }
                if ($type == 'float') {
                    $data = (float) $data;
                }
                if ($type == 'bool') {
                    $data = (bool) $data;
                }
                if ($type == 'string') {
                    $data = (string) $data;
                }
                if ($type == 'trim') {
                    $data = trim($data);
                }
                if ($type == 'xss') {
                    //TODO: XSS LOGIC

                }

            }
        }
        return $data;
    }

    // maybe will be beter to use str_replace substr
    public static function createProjectRootArr():array {
        $filePosition = explode( DIRECTORY_SEPARATOR, trim(PUBLIC_DIR));
        $documentRoot = explode("/", trim($_SERVER['DOCUMENT_ROOT']));
        $projectRoot = array_diff($filePosition ,$documentRoot ); // removing Document Root form File Root so we get only the Project Root
        return $projectRoot;
    }

    public static function createProjectRootStr():string {
        return implode("/",self::createProjectRootArr());

    }

    public static function getCleanLinkURI():string {
        $result =  substr($_SERVER["REQUEST_URI"],strlen($_SERVER["SCRIPT_NAME"]) - strlen("index.php") );
        $needle = "index.php/";
        if (strpos($result,$needle) === 0) {
            $result = substr($result, strlen($needle));
        }
        return $result;
    }

}