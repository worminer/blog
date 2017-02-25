<?php

namespace Controllers;

use MVC\App;

class Home
{
     /**
     * @param $params
     */
    public function index($params){

        $varArr = [
            "test" => "proba",
            "sub_title" => "- Index1@Home Subtitle"
        ];

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