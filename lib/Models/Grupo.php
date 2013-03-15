<?php

class Grupo {

    public $docente;
    public $horario = array();
    
    public function __construct($docente = '', $horario = array()) {
        $this->setDocente($docente);
        $this->setHorario($horario);
    }
    
    public function setDocente($docente) {
        $this->docente = $docente;
    }
    
    public function agregarHora(Horario $hora) {
        $this->horario[] = $hora;
    }
    
    public function setHorario($horario) {
        $this->horario = $horario;
    }
    
    public function getDocente() {
        return $this->docente;
    }
    
    public function getHorario() {
        return $this->horario;
    }
}
