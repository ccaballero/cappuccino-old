<?php

include 'lib/libs.php';
include 'lib/config.php';
include 'lib/conexion.php';

global $CONFIG;
$CONFIG = new Config();

$conector = Conector::getInstance();
$db = $conector->getConexion();

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

if(!empty($_POST)) {
    $paso = $_POST['paso'];
    $_page = $valid_pages[$paso];
}

foreach ($valid_pages as $page) {
    if ($page === $_page) {
        $PAGE = $_page;
        include 'layout/layout.php';
        exit;
    }
}

echo 'pagina invalida';
