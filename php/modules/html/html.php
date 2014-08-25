<?php
class html extends Module
{
    public function index()
    {
        $template=(isset($this->params['template'])?$this->params['template']:'').'.php';
        if(file_exists(ROOT_DIR.'/modules/html/views/'.$template)){
            $this->render($template);
        } 
    }
    public function setBgColor(){
        $params = $this->params;
        if(isset($params['image0'])){
            echo 'background: url('.URI::base().$params['image0'].');';
        }
    }
}
