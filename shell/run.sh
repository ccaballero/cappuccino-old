#!/bin/bash

cd /var/www/cappuchino.local/shell

php 01-get-pdf-php > ~/cappuchino.log
php 02-transform-text.php >> ~/cappuchino.log
php 03-parse-txt.php >> ~/cappuchino.log

