<?php

class Models_Nivel
{
    public $codigo;
    public $materias = array();

    public function __construct($codigo) {
        $this->setCodigo($codigo);
    }

    public function setCodigo($codigo) {
        $this->codigo = $codigo;
    }

    public function getCodigo(){
        return $this->codigo;
    }

    public function setMaterias($materias) {
        $this->materias = $materias;
    }

    public function getMaterias(){
        return $this->materias;
    }

    public function addMateria($codigo, Models_Materia $materia) {
        $this->materias[$codigo] = $materia;
    }

    public function __toString() {
        $return = $this->codigo . PHP_EOL;
        foreach ($this->getMaterias() as $materia) {
            $return .= $materia . PHP_EOL;
        }
        return $return;
    }
}
