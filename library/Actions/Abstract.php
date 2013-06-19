<?php

class Actions_Abstract
{
    protected $view;
    
    public function __construct(Views_View $view) {
        $this->setView($view);
        $this->setParams();
    }
    
    public function setView(Views_View $view) {
        $this->view = $view;
    }
    
    public function getView() {
        return $this->view;
    }
    
    private function setParams() {
        // change the hardcode
        $this->view->title = 'SCESI cappuchino';
        $this->view->controller = get_class($this);
    }
}
