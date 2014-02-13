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
                            $_tipo = substr($_materia, -1);
                            $_materia = trim(substr($_materia, 0, -1));
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

                        if ($materia->hasGrupo($_grupo)) {
                            $grupo = $materia->getGrupo($_grupo);
                        } else {
                            $grupo = new Models_Grupo($_grupo);
                            $materia->addGrupo($_grupo, $grupo);
                        }
                    }

                    $i = $i + 2;
                    if (preg_match('/^(?P<dia>(LU|MA|MI|JU|VI))$/',
                        $lines[$i], $output)) {
                        $_dia = $output['dia'];
                    }

                    $i = $i + 4;
                    if (preg_match('/^(?P<hora>\d{3,4})$/',
                        $lines[$i], $output)) {
                        $_hora = $output['hora'];
                    }

                    $i = $i + 2;
                    if (preg_match('/^(?P<aula>.*)$/',
                        $lines[$i], $output)) {
                        $_aula = $output['aula'];
                    }

                    $start = intval($_hora);
                    $horario = new Models_Horario(
                        $_dia, $start, 1, $_aula);
                    $grupo->addHorario($_dia, $start, $horario);

                    $_docente = $_apellidos . ' ' . $_nombres;

                    $horario->setDocente($_docente);
                    echo $materia->getNombre() . ' ' . $_tipo . ' ' . $_docente . PHP_EOL;
                    if ($_tipo == 'C') {
                        $grupo->setDocente($_docente);
                    }
                }
            }
        }

        return $carreras;
    }

    public function transform($file) {
        echo 'Serializando a JSON ' . PHP_EOL;

        $_carreras[] = array();
        $carreras = $this->parsePages($file);
        foreach ($carreras as $carrera) {
            $json_file = realpath(__DIR__ . '/../public/horarios/FCE/1-2014')
                . '/' . $carrera->getCodigo() . '.json';

            $json_carrera = $carrera->__toJSON();
            $json_carrera = str_replace('Ñ', '\u00d1', $json_carrera);
            file_put_contents($json_file, $json_carrera);

            echo $json_file . '...OK' . PHP_EOL;

            $_carrera = new StdClass();
            $_carrera->codigo = $carrera->getCodigo();
            $_carrera->nombre = $carrera->getNombre();
            $_carreras[] = $_carrera;
        }

        $file = realpath(__DIR__ . '/../public/horarios/FCE') . '/1-2014.json';
        file_put_contents($file, json_encode($_carreras));
    }
}

$file = __DIR__ . '/../public/horarios/FCE/2014-1.txt';
$parser = new ParseTxt();
$parser->transform($file);

