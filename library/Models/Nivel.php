<?php
class Models_Nivel {
    public $codigo;
    public $materias = array();

    public function __construct($codigo) {
        $this->setCodigo($codigo);
    }

    public function setCodigo($codigo) {
        $this->codigo = $codigo;
    }

    public function setMaterias($materias) {
        $this->materias = $materias;
    }
    public function getMaterias(){
        return $this->materias;
    }
    public function getCodigo(){
        return $this->codigo;
    }
    public function addMateria(Models_Materia $materia){
        $this->materias[]= Materia;
    }
}