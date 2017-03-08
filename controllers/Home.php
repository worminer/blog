<?php

namespace Controllers;


use MVC\DefaultController;


class Home extends DefaultController
{
    public function index(){

        if ($this->auth->isLogged()) {
            echo "user is logged in";
        }

        $arr = [
            "logged" => true,
            'user2' => "Stefan"
        ];

        // this is the default index so this will display the homepage
        $this->view->render("home/index",$arr);
    }
}