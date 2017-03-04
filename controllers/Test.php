<?php

namespace Controllers;


use MVC\DefaultController;


class Test extends DefaultController
{
    public function me(){

        if ($this->auth->isLogged()) {
            echo "user is logged in";
        }
        $arr = [
            "first"  => true,
            "second" => true,
            "third"  => true,
            "stringVariable"    => "test string/int/float",
            "boolVariable"      => true,
            "arrayVariable"     => ["parts","of","array"],
        ];
        // this is the default index so this will display the homepage
        $this->view->render("test/test",$arr);
    }
}