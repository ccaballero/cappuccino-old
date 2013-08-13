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

    public function __construct($directory) {
        $this->directory = $directory;
    }

    public function parsePages($input_file) {
        $content = file_get_contents($input_file);
        $pages = explode('', $content);

        $carrera = new Models_Carrera();

        foreach ($pages as $page) {
            $_carrera = $this->parser($page, explode("\n", $page));
            if (!empty($_carrera)) {
                $carrera->setCodigo($_carrera->getCodigo());
                $carrera->setNombre($_carrera->getNombre());
                $carrera->juntar($_carrera);
            }
        }

        return $carrera;
    }

    public function parser($content, $lines) {
        $output = array();

        $carrera = null;
        $nivel = null;
        $materia = null;
        $grupo = null;
        $horario = null;
        $docente = true;

        if (preg_match(
            '/Plan: LICENCIATURA EN (?P<nombre>.*)\((?P<codigo>\d+)\)/',
                $content, $output)) {
            $codigo = $output['codigo'];
            $nombre = $output['nombre'];
            if ($carrera == null) {
                $carrera = new Models_Carrera($codigo, $nombre);
            }
        }
        if (preg_match(
            '/Nivel de Estudios:(?P<codigo>[A-Z])/',
                $content, $output)) {
            $codigo = $output['codigo'];
            if ($nivel == null) {
                $nivel = new Models_Nivel($codigo);
                $carrera->addNivel($codigo, $nivel);
            }
        }

        foreach ($lines as $line) {
            if (preg_match(
                '/(?P<aux>\(?\*?\)?) ?(?P<codigo>\d{7}) (?P<nombre>.*) (?P<grupo>[0-9]{1,2}[a-zA-Z]?)/',
                    $line, $output) ||
                preg_match(
                '/(?P<aux>\(?\*?\)?) ?(?P<codigo>\d{7}) (?P<nombre>.*)/',
                    $line, $output)) {
                $codigo = $output['codigo'];
                $nombre = $output['nombre'];
                $docente = empty($output['aux']);

                if (!array_key_exists($codigo, $nivel->getMaterias())) {
                    $materia = new Models_Materia($codigo, $nombre);
                    $nivel->addMateria($codigo, $materia);
                } else {
                    $materias = $nivel->getMaterias();
                    $materia = $materias[$codigo];
                }

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

    // http://www.hawkee.com/snippet/1749/
    public function list_files() {
        $files = array();

        if (is_dir($this->directory)) {
            if ($handle = opendir($this->directory)) {
                while (($file = readdir($handle)) !== false) {
                    if ($file != '.' && $file != '..'
                            && substr($file, -4) == '.txt') {
                        $files[] = $file;
                    }
                }

                closedir($handle);
            }
        }

        return $files;
    }

    public function transform() {
        $dir = $this->directory;
        $files = $this->list_files();

        echo 'Serializando a JSON ' . PHP_EOL;
        $_carreras = array();

        foreach ($files as $file) {
            $json_file = substr($file, 0, -4) . '.json';
            $carrera = $this->parsePages($dir . $file);
            $json_carrera = $carrera->__toJSON();
            $json_carrera = str_replace('Ñ', '\u00d1', $json_carrera);
            file_put_contents($dir . $json_file, $json_carrera);

            $_carrera = new StdClass();
            $_carrera->codigo = $carrera->getCodigo();
            $_carrera->nombre = $carrera->getNombre();
            $_carreras[] = $_carrera;
            
            echo $json_file . '...OK' . PHP_EOL;
        }

        file_put_contents(substr($dir, 0, -1) . '.json', json_encode($_carreras));
    }
}

$dir = __DIR__ . '/../public/horarios/2-2013/';
$transform = new ParseTxt($dir);
$transform->transform();

//$dir = __DIR__ . '/../public/horarios/1-2013/760101.txt';
//$parser = new ParseTxt(__DIR__);
//$carrera = $parser->parsePages($dir);
//echo $carrera;
