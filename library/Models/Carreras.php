<?php

class Models_Carreras
{
    private $db = null;
    
    public function __construct($db) {
           $this->db = $db;
    }

    public function listarCarreras() {
        $sql = 'SELECT * FROM carrera';

        $query = mysql_query($sql) or die(mysql_error());
        
        $carreras = array();
        
        $row = mysql_fetch_row($query);
        while ($row != null) {
            $carrera = new Models_Carrera();
            $carrera->ident = $row[0];
            $carrera->gestion = $row[1];
            $carrera->codigo = $row[2];
            $carrera->nombre = $row[3];
            
            $carreras[] = $carrera;
            $row = mysql_fetch_row($query);
        }

        return $carreras;
    }
    
    public function getCarrera($carrera) {
        
    }
}
