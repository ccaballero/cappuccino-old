<?php

class Models_Grupo {
    public $codigo;
    public $docente;
    public $horarios = array();
    
    public function __construct($codigo ='', $docente = '', $horarios = array()) {
        $this->setCodigo($codigo);
        $this->setDocente($docente);
        $this->setHorarios($horarios);
    }
    public function setCodigo($codigo){
        $this->codigo=$codigo;
    }
    public function setDocente($docente) {
        $this->docente = $docente;
    }
    
    public function addHorario(Models_Horario $horario) {
        $this->horarios[] = $horario;
    }
    
    public function setHorarios($horarios) {
        $this->horarios = $horarios;
    }
    
    public function getDocente() {
        return $this->docente;
    }
    
    public function getHorarios() {
        return $this->horarios;
    }
}
