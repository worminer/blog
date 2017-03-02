<?php

namespace Controllers;


use MVC\DefaultController;


class Home extends DefaultController
{
    public function index(){
        // this is the default index so this will display the homepage
        $this->view->render("home/index");
    }
}