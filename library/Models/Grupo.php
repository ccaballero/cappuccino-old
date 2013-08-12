<?php

class Models_Grupo extends Models_Coleccion
{
    public $codigo;
    public $horarios = array();

    public function __construct($codigo ='', $docente = '', $horarios = array()) {
        $this->setCodigo($codigo);
        $this->setHorarios($horarios);
    }
    public function getCodigo(){
        return $this->codigo;
    }

    public function setCodigo($codigo){
        $this->codigo = $codigo;
    }

    public function addHorario($dia, $inicio, Models_Horario $horario) {
        $this->horarios[$dia.$inicio] = $horario;
    }

    public function setHorarios($horarios) {
        $this->horarios = $horarios;
    }

    public function getHorarios() {
        return $this->horarios;
    }

    public function __toString() {
        $return = '     -> ' . $this->codigo . PHP_EOL;
        foreach ($this->getHorarios() as $horario) {
            $return .= $horario;
        }
        return $return;
    }
    
    public function __toJSON() {
        $json_horarios = array();
        foreach ($this->getHorarios() as $horario) {
            $json_horarios[] = PHP_EOL . str_repeat(' ', 4 * 8) . $horario->__toJSON();
        }
        
        return '{' . PHP_EOL . 
            str_repeat(' ', 4 * 7) . '"codigo":"' . $this->getCodigo() .'",' . PHP_EOL .
            str_repeat(' ', 4 * 7) . '"horarios":[' . implode(',', $json_horarios) . ']' . PHP_EOL .
            str_repeat(' ', 4 * 6) . '}';
    }

    public function getColeccion() {
        return $this->getHorarios();
    }

    public function getId() {
        return $this->getCodigo();
    }

    public function setColeccion($coleccion) {
        $this->setHorarios($coleccion);
    }
}
