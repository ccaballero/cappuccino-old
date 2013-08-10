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
        $putput = array();
        $carreras = array();
        $carrera = null;
        
        foreach ($this->lines as $line) {
            if (preg_match(
                '/Plan: LICENCIATURA EN (?P<nombre>.*)\((?P<codigo>\d+)\)/',
                $line, $output)) {
                $codigo = $output['codigo'];
                if (!isset($carreras[$codigo])) {
                    $carrera = new Models_Carrera(
                        $output['codigo'], $output['nombre']);
                    $carreras[$codigo] = $carrera;
                } else {
                    $carrera = $carreras[$codigo];
                }
            }
            if (preg_match('/Nivel de Estudios:(?P<nivel>[A-Z])/',
                         $line, $output)) {
                $nivel = $output['nivel'];
                $carrera->addNivel($nivel, new Models_Nivel($nivel));
            }
       }
       return $carreras;
    }
}

$dir = __DIR__ . '/../data/horarios/1-2013/419701.txt';
$parser = new ParseTxt();
$parser->readFile($dir);
$tree = $parser->parser();
var_dump($tree);