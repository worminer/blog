<?php

class Test
{
    //TODO:implement me
    public function __construct()
    {
        if (DEBUG_MVC) {
            echo "<hr>I am Controller<br>".PHP_EOL;
        }

        new TestModel();
        new View("Test", [ "test" => "proba"]);
    }
}