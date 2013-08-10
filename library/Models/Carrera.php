<?php

class Models_Carrera {
    public $codigo;
    public $nombre;
    public $niveles = array();

    public function __construct($codigo, $nombre) {
        $this->setCodigo($codigo);
        $this->setNombre($nombre);
    }

    public function setCodigo($codigo) {
        $this->codigo = $codigo;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function addNivel(Models_Nivel $nivel) {
        $this->niveles[] = $nivel;
    }

    public function setNiveles($niveles) {
        $this->materias = $materias;
    }

    public function getCodigo() {
        return $this->codigo;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getNiveles() {
        return $this->niveles;
    }

    public function __toString() {
        return $this->nombre . ' (' . $this->codigo . ')';
    }
}
