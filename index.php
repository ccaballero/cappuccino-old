<?php

include 'lib/libs.php';
global $LIST;

global $CONFIG;
$CONFIG = new Config();

global $DB;
//$conector = Db_Conector::getInstance();
//$DB = $conector->getConexion();

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

try {
    $controlador = 'Actions_' . ucfirst($_page);
    $action = new $controlador();
    $componente = $action->run();

    $PAGE = $_page;
    include 'layout/layout.php';

} catch (Exception $e) {
    echo 'pagina invalida';
}

