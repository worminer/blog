<?php

class Router {
    public static $test = "azis";

    /**
     * Route constructor.
     */
    public function __construct()
    {
        self::$test = "not azis";
    }

}