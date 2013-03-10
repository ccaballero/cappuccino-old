<?php

class Carrera {
    public $codigo = array();
    public $materias = array();
    
    public function __construct($codigo = '', $materias = array()) {
        $this->setCodigo($codigo);
        $this->setMaterias($materias);
    }
    
    public function setCodigo($codigo) {
        $this->codigo = $codigo;
    }
    
    public function agregarMateria(Materia $materia) {
        $this->materias[] = $materia;
    }
    
    public function setMaterias($materias) {
        $this->materias = $materias;
    }
    
    public function getCodigo() {
        return $this->codigo;
    }
            
    public function getMaterias() {
        return $this->materias;
    }
}
