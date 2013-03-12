<?php

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
        $_count = $count + 1;
        include "index$_count.php";
        exit;
    }
}

echo 'pagina invalida';





