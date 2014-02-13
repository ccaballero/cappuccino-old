<?php

class Models_Nivel extends Models_Coleccion
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

    public function hasMateria($codigo) {
        return array_key_exists($codigo, $this->materias);
    }

    public function getMateria($codigo) {
        return $this->materias[$codigo];
    }

    public function __toString() {
        $return = $this->codigo . PHP_EOL;
        foreach ($this->getMaterias() as $materia) {
            $return .= $materia;
        }
        return $return;
    }

    public function getColeccion() {
        return $this->getMaterias();
    }

    public function getId() {
        return $this->getCodigo();
    }

    public function setColeccion($coleccion) {
        $this->setMaterias($coleccion);
    }
    
    public function __toJSON() {
        $json_materias = array();
        foreach ($this->getMaterias() as $materia) {
            $json_materias[] = PHP_EOL . str_repeat(' ', 4 * 4) . $materia->__toJSON();
        }
        
        return '{' .PHP_EOL.
            str_repeat(' ', 4 * 3) . '"codigo":"' . $this->getCodigo() .'",' . PHP_EOL .
            str_repeat(' ', 4 * 3) . '"materias":[' . implode(',', $json_materias) . ']' . PHP_EOL .
            str_repeat(' ', 4 * 2) . '}';
    }
}
