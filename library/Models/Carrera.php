<?php

class Models_Carrera extends Models_Coleccion
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

    public function hasNivel($codigo) {
        return array_key_exists($codigo, $this->niveles);
    }

    public function getNivel($codigo) {
        return $this->niveles[$codigo];
    }

    public function __toString() {
        $return = $this->nombre . ' (' . $this->codigo . ')' . PHP_EOL;
        foreach ($this->getNiveles() as $nivel) {
            $return .= $nivel . PHP_EOL;
        }
        return $return;
    }
    
    public function __toJSON() {
        $json_niveles = array();
        foreach ($this->getNiveles() as $nivel) {
            $json_niveles[] = PHP_EOL . str_repeat(' ', 4 * 2) . $nivel->__toJSON();
        }
        
        return '{' . PHP_EOL .
            str_repeat(' ', 4 * 1) . '"codigo":"' . $this->getCodigo() .'",' . PHP_EOL .
            str_repeat(' ', 4 * 1) . '"nombre":"' . $this->getNombre() . '",' . PHP_EOL .
            str_repeat(' ', 4 * 1) . '"niveles":[' . implode(',', $json_niveles) . ']' . PHP_EOL .
            str_repeat(' ', 4 * 0) . '}';
    }

    public function getColeccion() {
        return $this->getNiveles();
    }

    public function getId() {
        return $this->getCodigo();
    }

    public function setColeccion($coleccion) {
        $this->setNiveles($coleccion);
    }
}
