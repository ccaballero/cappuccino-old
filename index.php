<?php

global $PAGE;

function get_view() {
    global $PAGE;
    
    include 'vistas/' . $PAGE . '.php';
    return;
}

$valid_pages = array(
    'inicio',
    'carreras',
    'materias',
    'grupos',
    'horarios',
);

$_page = $_GET['page'];
if (empty($_page)) {
    $_page = 'inicio';
}

foreach ($valid_pages as $page) {
    if ($page === $_page) {
        $PAGE = $_page;
        include 'layout/layout.php';
        exit;
    }
}

echo 'pagina invalida';
