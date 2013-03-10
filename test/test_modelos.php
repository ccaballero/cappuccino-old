<?php

include 'modelos/Horario.php';
include 'modelos/Carrera.php';
include 'modelos/Grupo.php';
include 'modelos/Materia.php';

$horario = array(
    new Horario('lun', '8:15-9:45', '617'),
    new Horario('vie', '8:15-9:45', '691D'),
);

$grupos = array(
    new Grupo('CESPEDES GUIZADA BENITA', $horario),
);

$materias = array(
    new Materia('I/2013', '1803001', $grupos),
);

$carrera = new Carrera('419701', $materias);

var_dump($carrera);
