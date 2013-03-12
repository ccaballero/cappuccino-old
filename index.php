<?php

global $PAGE;

function get_view() {
    global $PAGE;
    
    include 'vistas/' . $PAGE . '.php';
    return;
}

$valid_pages = array(
    '',
    'carreras',
    'materias',
    'grupos',
    'horarios',
);

$_page = $_GET['page'];

foreach ($valid_pages as $count => $page) {
    if ($page === $_page) {
        $PAGE = $_page;
        $_count = $count + 1;
        include 'layout/layout.php';
        exit;
    }
}

echo 'pagina invalida';





