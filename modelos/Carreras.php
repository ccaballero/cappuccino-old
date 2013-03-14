<?php

class Carreras
{
    private $db = null;
    
    public function __construct($db) {
           $this->db = $db;
    }

    public function listarCarreras() {
        $sql = 'SELECT * FROM carrera';

        $query = mysql_query($sql_propuesta) or die(mysql_error());
        $row_propuesta = mysql_fetch_array($query_propuesta);

$id_propuesta=$row_propuesta['id_propuesta'];
$nombre_propuesta=htmlentities(utf8_decode($row_propuesta['nombre_propuesta']));
$tipoactividad=utf8_decode($row_propuesta['nombre_tipoactividad']);
$area=utf8_decode($row_propuesta['nombre_area']);
$estado=$row_propuesta['estado'];
$id_reserva=$row_propuesta['id_reserva'];
$fecha_reserva=substr($row_propuesta['fecha_reserva'],0,10);//solo una parte del timestamp
$fecha_mostrar=cambiaf_a_normal($fecha_reserva);
$ano_produccion=$row_propuesta['ano_produccion'];
$pais=utf8_decode($row_propuesta['nombre_pais']);
$ciudad=utf8_decode($row_propuesta['nombre_ciudad']);
$descripcion=nl2br(utf8_decode($row_propuesta['descripcion_propuesta']));
$leido=$row_propuesta['leido_usuario'];


        // SELECT * FROM carrera;
    }
    
    public function getCarrera($carrera) {
        
    }
}
