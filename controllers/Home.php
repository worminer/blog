<?php

namespace Controllers;

use MVC\App;
use MVC\InputData;
use MVC\Database\PdoMysql;

class Home
{
    private $app = null;

    /**
     * Home constructor.
     */
    public function __construct()
    {
        $this->app = App::getInstance();
    }

    /**
     * @param $params
     */
    public function index($params){
        var_dump($this->app->validator()->setRule('minLength',"someting",50)->validate());
        var_dump($this->app->validator()->getErrors());
        $varArr = [
            "test" => "proba",
            "sub_title" => "- Index1@Home Subtitle"
        ];
        $input = InputData::getInstance();
        $username = $input->post("username");
        App::render("home/index",$varArr);
    }
    public function index2(){
        $varArr = [
            "test" => "proba",
            "sub_title" => "- Index2@Home Subtitle"
        ];
        App::render("home/index",$varArr);
    }
}