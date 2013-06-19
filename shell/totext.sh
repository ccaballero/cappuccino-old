#!/bin/bash

GESTION='2013-I'

PROJECT_PATH='/home/jacobian/Proyectos/cappuchino'
PDF_PATH=$PROJECT_PATH'/data/horarios/'$GESTION'/pdf'

echo $PDF_PATH;
cd $PDF_PATH;

for f in *.pdf
do
    echo $f
done

