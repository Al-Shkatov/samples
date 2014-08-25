<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Module
 *
 * @author 1
 */
class Module
{

    protected $view;
    private $viewPath;

    /**
     *
     * @var Request 
     */
    protected $request;
    protected $params;
    public function __construct()
    {
        $this->view = new View();
        $admin = Factory::$admin ? '/admin' : '';
        $this->viewPath = ROOT_DIR . '/modules/' . get_class($this) . $admin . '/views/';
        $this->request = Factory::getRegistry()->request;
        
    }

    public function render($tpl)
    {
        $this->view->render($this->viewPath . $tpl);
    }
    public function setParams($params)
    {
        $this->params=$params;
    }
    
}

?>
