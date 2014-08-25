<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Plugin
 *
 * @author Administrator
 */
class Plugin
{
    protected $request;
    protected $view;
    private $viewPath;
    
    public function __construct()
    {
        $this->view = new View();
        $this->viewPath = ROOT_DIR . '/plugins/' . get_class($this) . '/views/';
        $this->request=  Factory::getRegistry()->request;
    }
    protected function registerEvent($event, $call)
    {
        Factory::getObserver()->addEvent($event, $call);
    }
    
    public function render($tpl)
    {
        return $this->view->renderFile($this->viewPath . $tpl);
    }
    
}

?>
