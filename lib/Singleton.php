<?php

class Singleton {
    private static $instance;
    
    private function __construct() {}
    
    public static function init() {
        $this->instance = $this;
    }
    
    public static function getInstance() {
        return $this->instance;
    }
}

$s = new Singleton();






