<?php

class Conector {
    private static $instance;
    
    private function __construct() {}
    
    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConexion() {
        global $CONFIG;
        
        $conexion = mysql_pconnect(
            $CONFIG->hostname,
            $CONFIG->username,
            $CONFIG->password
        )
            or trigger_error(mysql_error(),E_USER_ERROR); 
        mysql_select_db($CONFIG->database, $conexion);

        return $conexion;
    }
}
