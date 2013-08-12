<?php

class TransformText {
    protected $command = 'pdftotext';
    protected $directory;

    public function __construct($directory) {
        $this->directory = $directory;
    }

    // http://www.hawkee.com/snippet/1749/
    public function list_files() {
        $files = array();
        
        if (is_dir($this->directory)) {
            if ($handle = opendir($this->directory)) {
                while (($file = readdir($handle)) !== false) {
                    if ($file != '.' && $file != '..'
                            && substr($file, -4) == '.pdf') {
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
        
        echo 'Transformando los ficheros pdf' . PHP_EOL;
        foreach ($files as $file) {
            $txt_file = substr($file, 0, -4) . '.txt';
            echo $txt_file;
            exec($this->command . ' ' . $dir . $file);
            echo '...OK' . PHP_EOL;
        }
    }
}

$dir = __DIR__ . '/../public/horarios/1-2013/';
$transform = new TransformText($dir);
$files = $transform->transform();
