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
    public $names = array(
        '059801' => 'LIC. EN ECONOMIA',
        '089801' => 'LIC. EN CONTADURIA PUBLICA',
        '109401' => 'LIC. EN ADMINISTRACION DE EMPRESAS',
        '125091' => 'LIC. EN INGENIERIA COMERCIAL',
        '126091' => 'LIC. EN INGENIERIA FINANCIERA',
    );

    public $lines = array();

    public function parsePages($input_file) {
        $content = file_get_contents($input_file);
        $carreras = array();

        $carrera = null;
        $nivel = null;
        $materia = null;
        $grupo = null;
        $horario = null;

        $pages = explode('', $content);
        foreach ($pages as $page) {
            $lines = explode("\n", $page);
            for ($i = 0; $i < count($lines); $i++) {
                $line = $lines[$i];

                if (preg_match(
                    '/^\d{4} \d{1} (?P<carrera>.{6}) (?P<nivel>[ABCDEFGHIJ])$/',
                    $line, $output)) {

                    $_carrera = $output['carrera'];
                    if (array_key_exists($_carrera, $carreras)) {
                        $carrera = $carreras[$_carrera];
                    } else {
                        $carrera = new Models_Carrera(
                            $_carrera, $this->names[$_carrera]);
                        $carreras[$_carrera] = $carrera;
                    }

                    $_nivel = $output['nivel'];
                    if ($carrera->hasNivel($_nivel)) {
                        $nivel = $carrera->getNivel($_nivel);
                    } else {
                        $nivel = new Models_Nivel($_nivel);
                        $carrera->addNivel($_nivel, $nivel);
                    }

                    $i = $i + 2;
                    if (preg_match('/^(?P<sis>\d{9}) (?P<apellidos>.*)$/',
                        $lines[$i], $output)) {
                        $_sis = $output['sis'];
                        $_apellidos = $output['apellidos'];
                    }

                    $i = $i + 2;
                    if (preg_match('/^(?P<nombres>.*)$/',
                        $lines[$i], $output)) {
                        $_nombres = $output['nombres'];
                    }

                    $i = $i + 4;
                    if (preg_match('/^(?P<tipo>[CP])$/',
                        $lines[$i], $output)) {
                        $_tipo = $output['tipo'];
                    } else {
                        $_tipo = null;
                    }

                    $i = $i - 2;
                    if (preg_match('/^(?P<sis>\d{7}) (?P<materia>.*)$/',
                        $lines[$i], $output)) {
                        $_codigo = $output['sis'];
                        $_materia = $output['materia'];

                        if (empty($_tipo)) {
                            $_materia = trim(substr($_materia, 0, -1));
                            $_tipo = substr($_materia, -1);
                            $i = $i - 2;
                        }

                        if ($nivel->hasMateria($_codigo)) {
                            $materia = $nivel->getMateria($_codigo);
                        } else {
                            $materia = new Models_Materia($_codigo, $_materia);
                            $nivel->addMateria($_codigo, $materia);
                        }
                    }

                    $i = $i + 4;
                    if (preg_match('/^(?P<grupo>\d{2})$/',
                        $lines[$i], $output)) {
                        $_grupo = $output['grupo'];
                    }
                }
            }
        }

        return $carreras;
    }

    public function parser($content, $lines) {
        foreach ($lines as $line) {
            if (preg_match(
                '/(?P<aux>\(?\*?\)?) ?(?P<codigo>\d{7}) (?P<nombre>.*) (?P<grupo>[0-9]{1,2}[a-zA-Z]?)/',
                    $line, $output) ||
                preg_match(
                '/(?P<aux>\(?\*?\)?) ?(?P<codigo>\d{7}) (?P<nombre>.*)/',
                    $line, $output)) {

                if (isset($output['grupo'])) {
                    $id_grupo = $output['grupo'];
                    if (!array_key_exists($id_grupo, $materia->getGrupos())) {
                        $grupo = new Models_Grupo($id_grupo);
                        $materia->addGrupo($id_grupo, $grupo);
                    } else {
                        $grupos = $materia->getGrupos();
                        $grupo = $grupos[$id_grupo];
                    }
                }
            } else if (preg_match(
                '/^(?P<codigo>[0-9]{1,2}[a-zA-Z]?)$/', $line,$output)) {
                $codigo = $output['codigo'];
                if (!array_key_exists($codigo, $materia->getGrupos())) {
                    $grupo = new Models_Grupo($codigo);
                    $materia->addGrupo($codigo, $grupo);
                } else {
                    $grupos = $materia->getGrupos();
                    $grupo = $grupos[$codigo];
                }
            } else if (preg_match(
                '/^(?P<dia>(LU|MA|MI|JU|VI|SA)) (?P<inicio>\d{3,4})-(?P<final>\d{3,4})\((?P<aula>.*)\)$/',
                    $line, $output)) {
                $dia = $output['dia'];
                $inicio = intval($output['inicio']);
                $final = intval($output['final']);
                $duracion= intval(((intval($final/100)* 60 + ($final%100))-
                           (intval($inicio/100)*60+($inicio%100)))/45);
                $aula = $output['aula'];
                $horario = new Models_Horario($dia, $inicio, $duracion, $aula);
                $grupo->addHorario($dia, $inicio, $horario);
            } else if (preg_match('/^([A-Z¥ \.\']+|Por Designar ...)$/', $line, $output)) {
                $_docente = str_replace('¥', 'Ñ', $line);
                $horario->setDocente($_docente);
                if ($docente) {
                    $grupo->setDocente($_docente);
                    $docente = true;
                }
            }
       }

       return $carrera;
    }

    public function transform($file) {
        //echo 'Serializando a JSON ' . PHP_EOL;
        $carreras = $this->parsePages($file);
        foreach ($carreras as $carrera) {
            echo $carrera; 
        }

        //$_carreras = array();

        //$json_file = substr($file, 0, -4) . '.json';

        //$json_carrera = $carrera->__toJSON();
        //$json_carrera = str_replace('Ñ', '\u00d1', $json_carrera);
        //file_put_contents($dir . $json_file, $json_carrera);

        //$_carrera = new StdClass();
        //$_carrera->codigo = $carrera->getCodigo();
        //$_carrera->nombre = $carrera->getNombre();
        //$_carreras[] = $_carrera;
        
        //echo $json_file . '...OK' . PHP_EOL;

        //file_put_contents(substr($dir, 0, -1) . '.json', json_encode($_carreras));
    }
}

$file = __DIR__ . '/../public/horarios/FCE/2014-1.txt';
$parser = new ParseTxt();
$parser->transform($file);

//echo $carrera;

