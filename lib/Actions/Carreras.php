<?php

class Actions_Carreras
{
    public function run() {
        global $DB;
        $carreras = new Models_Carreras($DB);
        $carreras = $carreras->listarCarreras();

        global $LIST;
        $LIST = $carreras;
    }
}
