<?php

include __DIR__ . '/../lib/pdf2text.php';

echo 'Importador de horarios en formato pdf' . PHP_EOL;
echo pdf2text(__DIR__ . '/../data/horarios/114071.pdf');
