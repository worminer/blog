<?php
require_once ("constants.php");
require_once ("autoload.php");

class app {

    private $urlTokens = [];

    public function start(){
        echo "i am running<pre>".PHP_EOL;
        new Test();
    }



    public function getUrlParams():array {
        $filePosition = explode( DIRECTORY_SEPARATOR, trim(PUBLIC_DIR));
        $documentRoot = explode("/", trim($_SERVER['DOCUMENT_ROOT']));
        $urlRequest = array_filter(explode("/", trim($_SERVER['REQUEST_URI'])));
        $projectRoot = array_diff($filePosition ,$documentRoot ); // removing Document Root form File Root so we get only the Project Root
        $urlParams = array_diff($urlRequest,$projectRoot); // we remove Project Root from URL params so we get the Request Parameters
        return $urlParams;
    }
}