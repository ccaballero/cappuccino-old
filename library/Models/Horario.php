<?php

class Models_Horario {

    public $dia;
    public $hora;
    public $aula;
    public $duracion;

    public function __construct($dia = '', $hora = '', $duracion = '', $aula = '') {
        $this->setDia($dia);
        $this->setHora($hora);
        $this->setDuracion($duracion);
        $this->setAula($aula);
    }

    public function setDuracion($duracion) {
        $this->duracion = $duracion;
    }

    public function setDia($dia) {
        $this->dia = $dia;
    }

    public function setHora($hora) {
        $this->hora = $hora;
    }

    public function setAula($aula) {
        $this->aula = $aula;
    }

    public function getDuracion() {
        return $this->duracion;
    }
    
    public function getDia() {
        return $this->dia;
    }

    public function getHora() {
        return $this->hora;
    }

    public function getAula() {
        return $this->aula;
    }

}
