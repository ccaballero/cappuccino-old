<?php

class Models_Horario
{
    public $dia;
    public $hora;
    public $aula;
    public $duracion;
    public $docente;

    public function __construct(
            $dia = '', $hora = '', $duracion = '', $aula = '', $docente = '') {
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

    public function setDocente($docente) {
        $this->docente = $docente;
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

    public function getDocente() {
        return $this->docente;
    }

    public function __toString() {
        return '          - '
            . $this->dia . ' '
            . $this->hora . '-'
            . ($this->hora + $this->duracion) . ' ('
            . $this->aula . ') '
            . $this->docente . PHP_EOL;
    }

    public function __toJSON() {
        return '{' . PHP_EOL .
            str_repeat(' ', 4 * 9) . '"dia":"' . $this->getDia() . '",' . PHP_EOL .
            str_repeat(' ', 4 * 9) . '"hora":"' . $this->getHora() .'",' . PHP_EOL .
            str_repeat(' ', 4 * 9) . '"duracion":"' . $this->getDuracion() .'",' . PHP_EOL .
            str_repeat(' ', 4 * 9) . '"aula":"' . $this->getAula() .'",' . PHP_EOL .
            str_repeat(' ', 4 * 9) . '"docente":"' . $this->getDocente() .'"' . PHP_EOL .
        str_repeat(' ', 4 * 8) . '}';
    }
}
