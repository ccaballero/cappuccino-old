<?php

class Views_View
{
    private $layout_path;
    private $layout;
    
    public function setLayoutPath($layout_path) {
        $this->layout_path = $layout_path;
    }
    
    public function getLayoutPath() {
        return $this->layout_path;
    }
    
    public function setLayout($layout) {
        $this->layout = $layout;
    }
    
    public function getLayout() {
        return $this->layout;
    }

    public function render($file = '') {
        $tpl = $file;
        if (empty($file)) {
            $tpl = $this->getLayout();
        }
        
        // Based in code of Fabien Potencier. (symfony 1.4)
        // sfPHPView.class.php
        // render method
        ob_start();
        ob_implicit_flush(false);
        try {
            require($this->getLayoutPath() . $tpl);
        } catch (Exception $e) {
            // need to end output buffering before throwing the exception #7596
            ob_end_clean();
            throw $e;
        }

        return ob_get_clean();
    }
    
    public function menu() {
        return $this->render('menu.php');
    }
    
    public function content() {
        $controller = strtolower(substr($this->controller, 8));
        return $this->render($controller . '.php');
    }
}
