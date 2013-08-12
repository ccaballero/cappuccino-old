<?php

class GetPdf
{
    protected $url = 'http://fcyt.umss.edu.bo/horarios/';
    protected $pdf_dir;
    
    public function __construct() {
        $this->pdf_dir = __DIR__ . '/../public/horarios/';
    }


    public function getPage($url) {
        // create curl resource
        $ch = curl_init();
        // set url
        curl_setopt($ch, CURLOPT_URL, $url);
        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // $output contains the output string
        $output = curl_exec($ch);
        // close curl resource to free up system resources
        curl_close($ch);

        return $output;
    }
    
    public function parse() {
        $output = $this->getPage($this->url);
        $matches = array();

        preg_match('/HORARIOS (?P<gestion>[12]-\d{4})/', $output, $matches);
        $gestion = $matches['gestion'];
        
        echo 'Consiguiendo los horarios para la gestion ' . $gestion . PHP_EOL;

        preg_match_all(
            '/<a href="(?P<url>.*\/)(?P<file>.*\.pdf)"/', $output, $matches);

        $dir = $this->pdf_dir . $gestion;
        if (file_exists($dir) || mkdir($dir)) {
            foreach ($matches['url'] as $i => $url) {
                $filename = $matches['file'][$i];
                $destination = realpath($dir) . '/' . $filename;
                
                echo 'Descargando el fichero '
                    . $filename;
                $pdf_content = $this->getPage($url . $filename);
                file_put_contents($destination, $pdf_content);
                
                echo '...OK' . PHP_EOL;
            }
        }
    }
}

$parser = new GetPdf();
$parser->parse();
