<?php

class Models_Carrera
{
    protected $codigo;
    protected $nombre;
    protected $niveles = array();

    public function __construct($codigo = '', $nombre = '') {
        $this->setCodigo($codigo);
        $this->setNombre($nombre);
    }

    public function setCodigo($codigo) {
        $this->codigo = $codigo;
    }

    public function getCodigo() {
        return $this->codigo;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function addNivel($codigo, Models_Nivel $nivel) {
        $this->niveles[$codigo] = $nivel;
    }

    public function setNiveles($niveles) {
        $this->niveles = $niveles;
    }

    public function getNiveles() {
        return $this->niveles;
    }

    public function __toString() {
        $return = $this->nombre . ' (' . $this->codigo . ')' . PHP_EOL;
        foreach ($this->getNiveles() as $nivel) {
            $return .= $nivel . PHP_EOL;
        }
        return $return;
    }
}
