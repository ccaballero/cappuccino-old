<?php

class Horario {
    public $dia;
    public $hora;
    public $aula;

    public function __construct($dia = null, $hora = null, $aula = null) {
        $this->setDia($dia);
        $this->setHora($hora);
        $this->setAula($aula);
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
