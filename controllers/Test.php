<?php

class Test
{
    //TODO:implement me
    public function __construct()
    {
        echo "I am Controller".PHP_EOL;
        new TestModel();
        new View("Test", [ "test" => "proba"]);
    }
}