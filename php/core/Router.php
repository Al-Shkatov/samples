<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Router
 *
 * @author 1
 */
class Router {

    private $allowed_modules = array(
        'moderator' => array('menu' => array('index'), 'content' => array(), 'breadcrumbs' => array(), 'user' => array('userInfo', 'logout')),
        'guest' => array('user' => array('login'))
    );

    private function filterModules($modules) {
        $role = Auth::getUser()->role;
        if ($role != 'admin') {
            $new_modules = array();
//            $allowed = empty($role) ? $this->allowed_modules['guest'] : $this->allowed_modules[$role];
            $allowed = $this->allowed_modules['guest'];
            for ($i = 0; $i < sizeof($modules); $i++) {
                if (isset($allowed[$modules[$i]->module])) {
                    if (empty($allowed[$modules[$i]->module]) || in_array($modules[$i]->params['action'], $allowed[$modules[$i]->module])) {
                        $new_modules[] = $modules[$i];
                    }
                }
            }
            return array_values($new_modules);
        } else {
            return $modules;
        }
    }

    public function __construct() {
        $uri = Factory::getURI();
        $route = $uri->route();
        $cache = new Cache($route);
        $request = new Request();
        $ajax_page = $request->getParam('ajax_page');
        if (!$this->is_ajax() || !empty($ajax_page)) {
            if (!$cache->is_cached()) {
                $model = new RouterModel();
                $modules = $model->getModulesByRoute($route);
                if (Factory::$admin) {
                    $modules = $this->filterModules($modules);
                }
                $layout = $model->getLayoutByRoute($route);
                new Renderer($layout, $modules, $route);
            } else {
                echo $cache->get();
            }
        } else {
            $req = $this->parseAjaxRequest();
            $module = Loader::loadModule($req['module']);
            if (method_exists($module, $req['action'])) {
                echo $module->$req['action']();
            }
            exit();
        }
    }

    private function is_ajax() {
        Factory::$ajax = (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') || isset($_GET['ajax']);
        return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') || isset($_GET['ajax']);
    }

    private function parseAjaxRequest() {
        $parts = Factory::getURI()->params();
        if ($parts[0] == 'admin') {
            array_shift($parts);
        }
        Factory::getRegistry()->request->setParam('action',$parts[1]);
        return array('module' => $parts[0], 'action' => $parts[1]);
    }

}

?>
