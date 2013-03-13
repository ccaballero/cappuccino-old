<?php

session_start();


global $PAGE;

function get_view() {
    global $PAGE;
    
    include 'vistas/' . $PAGE . '.php';
    return;
}
