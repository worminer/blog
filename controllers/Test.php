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
            "ifVariable"  => true,
            "unlessVariable" => false,
            "third"  => true,
            "stringVariable"    => "test string/int/float",
            "boolVariable"      => true,
            "arrayVariable"     => ["parts","of","array"],
            "emptyArray"     => [],
            "linksKeyValues"     => ["home" => "/",
                                    "about" => "/home/about",
                                    "login" => "/user/login",
                                    "test" => "/test/test"],
            "linksValues"        => ["/","/home/about","/user/login","/test/test",],
        ];


        // this is the default index so this will display the homepage
        $this->view->render("test/test",$arr);
    }
}