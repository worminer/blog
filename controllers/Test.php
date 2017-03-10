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
            "ifVariable"  => false,
            "unlessVariable" => false,
            "third"  => true,
            "sub_title"     => "test Subtitle",
            "stringVariable"    => "test string/int/float",
            "boolVariable"      => true,
            "arrayVariable"     => ["parts","of","array"],
            "emptyArray"     => [],
            "linksKeyValues"     => [
                "home" => "/",
                "about" => "/home/about",
                "login" => "/user/login",
                "test" => "/test/test"
            ],
            "linksValues"        => [
                "/",
                "/home/about",
                "/user/login",
                "/test/test",
            ],
            "dbresults"     => [
                [
                    "name" => "Pesho Petrov",
                    "apples" => 325,
                    "city" => "Sofia",
                ],
                [
                    "name" => "Ivan Ivanov",
                    "apples" => 325,
                    "city" => "Plovdiv",
                ]
            ],
            "dbResultsIndexed"        => [
                [
                    "Pesho Petrov",
                    325,
                    "Sofia"
                ],
                [
                    "Ivan Ivanov",
                    25,
                    "Plovdiv",
                ]
            ]
        ];


        // this is the default index so this will display the homepage
        $this->view->render("test/test",$arr);
    }
}