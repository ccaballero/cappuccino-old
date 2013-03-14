<?php

include __DIR__ . '/../lib/pdf2text.php';

echo 'Importador de horarios en formato pdf' . PHP_EOL;

for ($i = 1; $i <= 8; $i++) {
    echo pdf2text(__DIR__ . '/../data/horarios/pg_000' . $i . '.pdf');
}
