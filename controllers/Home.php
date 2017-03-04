<?php

namespace Controllers;


use MVC\DefaultController;


class Home extends DefaultController
{
    public function index(){

        if ($this->auth->isLogged()) {
            echo "user is logged in";
        }

        // this is the default index so this will display the homepage
        $this->view->render("home/index",["logged" => true]);
    }
}