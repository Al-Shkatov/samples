<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Application
 *
 * @author 1
 */
class Application
{

    public function __construct($admin = "")
    {

    }

    public function run($admin = "")
    {
        
        $observer = Factory::getObserver();
        if (defined('DEBUG') && DEBUG == true)
        {
            $profiler = Factory::getProfiler();
            $observer->addEvent('startApp', array($profiler, 'start'));
            $observer->addEvent('endApp', array($profiler, 'end'));
        }
        
        $observer->trigger('startApp', __CLASS__);
        $observer->addEvent('purgeCache', array('Cache', 'purge', Factory::getURI()->route()));
        $registry = Factory::getRegistry();
       
        $registry->config = new Config();
        $registry->request = new Request();

        $registry->flash = Flash::instance();

        Factory::getDatabase();
        $this->checkUser($admin);
        $this->_initPlugins();

        new Router();
        $observer->trigger('endApp', __CLASS__);
        
        
        if (defined('DEBUG') && DEBUG == true)
        {
            $dView=new View();
            $dView->data=Factory::getProfiler()->getLog();
            $dView->render(ROOT_DIR.'/debug/debug.php');
        }
        
    }
    
    private function checkUser($admin = ""){
        $uri = Factory::getURI();
        $route = $uri->route();
        if ($admin == 'admin')
        {
            Factory::$admin = true;

            if (!Auth::isAdmin() && $route != 'admin/login')
            {
                $uri->redirect('admin/login');
                exit();
            }
            if ($route == 'admin' || $route == 'admin/')
            {
                $uri->redirect('admin/index');
                exit();
            }
        } else
        {
            Factory::$admin = false;
            
            if ($route == "")
            {
                $uri->setRoute('index');
            }
            
        }
        include_once 'functions.php';
    }
    
    

    private function _initPlugins()
    {
        $plugins = scandir(ROOT_DIR . '/plugins');
        foreach ($plugins as $plugin)
        {
            if ($plugin != '.' && $plugin != '..')
            {
                if (is_file(ROOT_DIR . '/plugins/' . $plugin . '/index.php'))
                {
                    include_once ROOT_DIR . '/plugins/' . $plugin . '/index.php';
                    if(is_dir(ROOT_DIR . '/plugins/' . $plugin . '/model/'))
                    {
                        $pluginModels=scandir(ROOT_DIR . '/plugins/' . $plugin . '/model/');
                        foreach($pluginModels as $pluginModel)
                        {
                            if($pluginModel!='.' && $pluginModel!='..'){
                                include_once ROOT_DIR . '/plugins/' . $plugin . '/model/'.$pluginModel;
                            }
                        }
                    }
                    new $plugin();
                }
            }
        }
    }

}

?>
