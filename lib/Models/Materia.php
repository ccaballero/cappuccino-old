<?php

class Materia {
    public $semestre;
    public $codigo;
    public $grupos = array();
    
    public function __construct($semestre = '', $codigo = '', $grupos = array()) {
        $this->setSemestre($semestre);
        $this->setCodigo($codigo);
        $this->setGrupos($grupos);
    }
    
    public function setSemestre($semestre) {
        $this->semestre = $semestre;
    }

    public function setCodigo($codigo) {
        $this->codigo = $codigo;
    }
    
    public function agregarGrupo(Grupo $grupo) {
        $this->grupos[] = $grupo;
    }
    
    public function setGrupos($grupos) {
        $this->grupos = $grupos;
    }
    
    public function getSemestre() {
        return $this->semestre;
    }

    public function getCodigo() {
        return $this->codigo;
    }
    
    public function getGrupos() {
        return $this->grupos;
    }
}
