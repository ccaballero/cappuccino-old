<?php

abstract class Models_Coleccion {
    public function igual($otro) {
        return $this->getId() === $otro->getId();
    }
    
    public abstract function getId();
    
    public function juntar(Models_Coleccion $otro) {
        $array = array();

        foreach ($this->getColeccion() as $key => $element) {
            if (array_key_exists($key, $array)) {
                $array[$key]->juntar($element);
            } else {
                $array[$key] = $element;
            }
        }
        foreach ($otro->getColeccion() as $key => $element) {
            if (array_key_exists($key, $array)) {
                $array[$key]->juntar($element);
            } else {
                $array[$key] = $element;
            }
        }
        
        $this->setColeccion($array);
    }
     
    public abstract function getColeccion();
    public abstract function setColeccion($coleccion);
}
