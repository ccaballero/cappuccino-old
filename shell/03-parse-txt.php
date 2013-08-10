<?php

defined('APPLICATION_PATH') ||
        define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/..'));

set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/library'),
    get_include_path(),
)));

function __autoload($class) {
    @include str_replace('_', '/', $class) . '.php';
}

class ParseTxt {
    public $lines = array();

    public function readFile($input_file) {
        //$input_file = 'tests';
        $handle = @fopen($input_file, 'r');
        $lines = array();
        if ($handle) {
            while (($buffer = fgets($handle)) !== false) {
                $buffer = substr($buffer, 0, -1);
                $lines[] = $buffer;
            }
            if (!feof($handle)) {
                echo "Error: unexpected fgets() fail\n";
            }
            fclose($handle);
        }
        $this->lines = $lines;
    }

    public function parser() {
        $output = array();
        $carreras = array();

        $group_flag = false;
        
        $carrera = null;
        $nivel = null;
        $materia = null;
        
        foreach ($this->lines as $line) {
            if (preg_match(
                '/Plan: LICENCIATURA EN (?P<nombre>.*)\((?P<codigo>\d+)\)/',
                    $line, $output)) {
                $codigo = $output['codigo'];
                $nombre = $output['nombre'];
                if (!isset($carreras[$codigo])) {
                    $carrera = new Models_Carrera($codigo, $nombre);
                    $carreras[$codigo] = $carrera;
                } else {
                    $carrera = $carreras[$codigo];
                }
            }
            if (preg_match(
                '/Nivel de Estudios:(?P<codigo>[A-Z])/',
                    $line, $output)) {
                $codigo = $output['codigo'];
                if (!array_key_exists($codigo, $carrera->getNiveles())) {
                    $nivel = new Models_Nivel($codigo);
                    $carrera->addNivel($codigo, $nivel);
                } else {
                    $nivel = $carrera->getNiveles()[$codigo];
                }
            }
            if (preg_match(
                '/\(?\*?\)? ?(?P<codigo>\d{7}) (?P<nombre>.*)/',
                    $line, $output)) {
                $codigo = $output['codigo'];
                $nombre = $output['nombre'];
                if (!array_key_exists($codigo, $nivel->getMaterias())) {
                    $materia = new Models_Materia($codigo, $nombre);
                    $nivel->addMateria($codigo, $materia);
                } else {
                    $materia = $nivel->getMaterias()[$codigo];
                }
//                $group_flag = false;
//                $group_flag = true;
            }
       }
       
       return $carreras;
    }
}

$dir = __DIR__ . '/../data/horarios/1-2013/419701.txt';
$parser = new ParseTxt();
$parser->readFile($dir);
$carreras = $parser->parser();

echo implode(PHP_EOL, $carreras);
