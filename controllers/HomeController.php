<?php


class HomeController
{



    public function __construct(string $action = null, array $params = [])
    {

        if (!empty($action)) {
            if (method_exists($this, $action)) {
                $this->$action($params);
            } else {
                throw new \Exception("This action:{$action} does not exist! ");
            }
        }
    }

    public function index(){
        if (DEBUG_MVC) {
            echo "<hr>I am HomeController->index<br>".PHP_EOL;
        }
        $varArr = ["test" => "proba"];

        new View("home/index",$varArr);
    }
}