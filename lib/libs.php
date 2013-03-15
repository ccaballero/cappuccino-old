<?php

session_start();

function __autoload($class) {
    include str_replace('_', '/', $class) . '.php';
}

global $PAGE;

function get_view() {
    global $PAGE;
    
    include 'vistas/' . $PAGE . '.php';
    return;
}
