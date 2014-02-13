<?php

class Models_Materia extends Models_Coleccion
{
    public $codigo;
    public $nombre;
    public $grupos = array();

    public function __construct(
            $codigo = '', $nombre = '', $grupos = array()) {
        $this->setCodigo($codigo);
        $this->setNombre($nombre);
        $this->setGrupos($grupos);
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function setCodigo($codigo) {
        $this->codigo = $codigo;
    }

    public function getCodigo() {
        return $this->codigo;
    }

    public function setGrupos($grupos) {
        $this->grupos = $grupos;
    }

    public function getGrupos() {
        return $this->grupos;
    }

    public function addGrupo($codigo, Models_Grupo $grupo) {
        $this->grupos[$codigo] = $grupo;
    }

    public function hasGrupo($codigo) {
        return array_key_exists($codigo, $this->grupos);
    }

    public function getGrupo($codigo) {
        return $this->grupos[$codigo];
    }

    public function __toString() {
        $return = ' * (' . $this->codigo .') '.$this->nombre . PHP_EOL;
        foreach ($this->getGrupos() as $grupo) {
            $return .= $grupo . PHP_EOL;
        }
        return $return;
    }
    public function __toJSON() {
        $json_grupos = array();
        foreach ($this->getGrupos() as $grupo) {
            $json_grupos[] = PHP_EOL . str_repeat(' ', 4 * 6) . $grupo->__toJSON();
        }
        
        return '{' . PHP_EOL .
            str_repeat(' ', 4 * 5) . '"codigo":"' . $this->getCodigo() .'",' . PHP_EOL .
            str_repeat(' ', 4 * 5) . '"nombre":"' . $this->getNombre() . '",' . PHP_EOL .
            str_repeat(' ', 4 * 5) . '"grupos":[' . implode(',', $json_grupos) . ']' . PHP_EOL .
            str_repeat(' ', 4 * 4) . '}';
    }

    public function getColeccion() {
        return $this->getGrupos();
    }

    public function getId() {
        return $this->getCodigo();
    }

    public function setColeccion($coleccion) {
        $this->setGrupos($coleccion);
    }
    
}
