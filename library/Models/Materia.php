<?php

class Models_Materia {
    public $nombre;
    public $codigo;
    public $grupos = array();
    
    public function __construct( $codigo = '', $nombre = '',$grupos = array()) {
        $this->setCodigo($codigo);
        $this->setNombre($nombre);
        $this->setGrupos($grupos);
    }
    
    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function setCodigo($codigo) {
        $this->codigo = $codigo;
    }
    
    public function addGrupo(Models_Grupo $grupo) {
        $this->grupos[] = $grupo;
    }
    
    public function setGrupos($grupos) {
        $this->grupos = $grupos;
    }
    
    public function getNombre() {
        return $this->nombre;
    }

    public function getCodigo() {
        return $this->codigo;
    }
    
    public function getGrupos() {
        return $this->grupos;
    }
}
