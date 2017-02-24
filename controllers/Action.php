<?php

namespace Controllers;
class Action
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

            echo "<hr>I am action controller->index<br>".PHP_EOL;

//        $varArr = ["test" => "proba"];
//
//        new View("home/index",$varArr);
    }
    public function index2(){

            echo "<hr>I am action controller->indexx!!<br>".PHP_EOL;

//        $varArr = ["test" => "proba"];
//
//        new View("home/index",$varArr);
    }
}