<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PartialRender
 *
 * @author 1
 */
class PartialRender {
    public function __construct() {
        $this->base_url = Factory::getURI()->base();
    }
    public function render($tpl)
    {
        if(is_file($tpl)){
            include $tpl;
        }else{
            trigger_error('View Not Found',E_USER_ERROR);
        }
    }
    
    public function renderPartial($tpl,$vars=array())
    {
        $renderer=new PartialRender();
        foreach ($vars as $name=>$value)
        {
            $renderer->$name=$value;
        }
        $renderer->render(ROOT_DIR.$tpl);
    }
}

?>
