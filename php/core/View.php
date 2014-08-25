<?php

/**
 * Рендеринг хтмл
 *
 */
class View
{
    private $variables=array();
    public function __construct()
    {
        $this->base_url = Factory::getURI()->base();
        $this->config = Factory::getRegistry()->config;
    }
    public function __call($name, $arguments)
    {
        $return=Factory::getObserver()->trigger($name,$arguments);
        return is_array($return)?reset($return):$return;
    } 
    
    public function render($tpl)
    {
        include $tpl;
    }
    public function renderFile($tpl){
        ob_start();
        include $tpl;
        $content=ob_get_contents();
        ob_end_clean();
        return $content;
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
