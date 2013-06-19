<?php

defined('APPLICATION_PATH') ||
define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../'));

set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/library'),
    get_include_path(),
)));

// autoload
function __autoload($class) {
    include str_replace('_', '/', $class) . '.php';
}

// router
$accepted_requests = array(
    ''         => 'Actions_Inicio',
    'carreras' => 'Actions_Carreras',
    'materias' => 'Actions_Materias',
    'grupos'   => 'Actions_Grupos',
    'horarios' => 'Actions_Horarios',
);

// view
$view = new Views_View();
$view->setLayoutPath(APPLICATION_PATH . '/templates/');
$view->setLayout('default.php');

$request = $_GET['page'];
$controller = new Actions_404($view);

if (array_key_exists($request, $accepted_requests)) {
    $controller = new $accepted_requests[$request]($view);
}

$controller->run();
echo $controller->getView()->render();
