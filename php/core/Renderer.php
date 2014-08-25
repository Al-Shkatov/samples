<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Renderer
 *
 * @author Administrator
 */
class Renderer {

    protected $_layout;
    protected $_views;
    protected $_modules = array();
    private static $contentPositions = array();

    /**
     *
     * @var Document
     */
    private $_document;
    private $_route;

    public function __construct($layout, $modules, $route) {
        $admin = Factory::$admin ? '/admin/' : '/public/';
        $this->_document = Factory::getDocument();
        $this->_layout = ROOT_DIR . $admin . 'layout/' . $layout . '.php';

        $this->_views = ROOT_DIR . $admin . 'views/';
        $this->_formatModules($modules);
        $this->_route = $route;
        $this->_loadLayout();
    }

    /**
     *
     * @var stdClass $module
     * @example
     * $module=new stdClass();
     * $module->module='name of module';
     * $module->params=array('action'=>'action to render',...'other params to need in module');
     * Renderer::renderModule($module);
     * */
    public static function renderModule($module, $position = "", $after = false, $replace = false) {
        if ($position != "") {
            ob_start();
            self::moduleLoad($module);
            $content = ob_get_contents();
            if ($replace) {
                self::$contentPositions[$position] = $content;
            } else {
                if ($after) {
                    self::$contentPositions[$position].=$content;
                } else {
                    self::$contentPositions[$position] = $content . self::$contentPositions[$position];
                }
            }
            ob_end_clean();
        } else {
            return self::moduleLoad($module);
        }
        //return $this->_loadModule($module);
    }

    private function _loadLayout() {
        if (is_file($this->_layout)) {
            $time = microtime(true);
            $content = file_get_contents($this->_layout);
            $positions = $this->_parse($content);
            $content = $this->_loadPositions($positions, $content);
            $executionTime = microtime(true) - $time;
            foreach (self::$contentPositions as $position => $positionContent) {
                //if($positionContent!=''){
                $content = str_replace('{{' . $position . '}}', $positionContent, $content);
                //}
            }
            Factory::getObserver()->trigger('AfterLoadAllPositions', $position);
            preg_match_all('[\[\[(.*)\]\]]', $content, $documentParts);
            foreach ($documentParts[1] as $part) {
                $partContent = "";
                $tmp = explode(',', $part);
                $method = $tmp[0];
                if (method_exists($this->_document, $method)) {
                    if (sizeof($tmp) > 1) {
                        array_shift($tmp);
                        $partContent = call_user_func_array(array($this->_document, $method), $tmp);
                    } else {
                        $partContent = $this->_document->$method();
                    }
                }
                $content = str_replace('[[' . $part . ']]', $partContent, $content);
            }
            if ($executionTime > 0.02) {
                $cache = new Cache($this->_route);
                $cache->put($content);
            }
            $request = new Request();
            $ajax_page = $request->getParam('ajax_page');
            if (!empty($ajax_page)) {
                $res = array();
                $res['route'] = $this->_route;
                $res['content'] = $content;
                $doc = Factory::getDocument();
                $res['title'] = $doc->title();
                die('{"jsonrpc" : "2.0", "result" : ' . json_encode($res) . '}');
            } else {
                echo $content;
            }
        } else {
            //Factory::getURI()->redirect('404');
        }
    }

    private function _parse($content) {
        preg_match_all('[\{\{(.*)\}\}]', $content, $positions);
        return $positions[1];
    }

    private function _loadPositions($positions, $content) {
        foreach ($positions as $position) {
            self::$contentPositions[$position] = "";
        }
        foreach ($positions as $position) {
            $prePosition = Factory::getObserver()->trigger('BeforeLoadPosition', $position);
            $prePosition = reset($prePosition);
            if (is_file($this->_views . $position . '.php')) {
                $moduleContent = "";
                if (isset($this->_modules[$position])) {
                    foreach ($this->_modules[$position] as $module) {
                        //_dbg($module,$position);
                        $moduleContent.=$this->_loadModule($module);
                    }
                }
                $positionContent = str_replace('{module}', $moduleContent, file_get_contents($this->_views . $position . '.php'));
                $remakedContent = Factory::getObserver()->trigger('AfterLoadPosition', $position, $positionContent);
                $remakedContent = reset($remakedContent);
                if (!empty($remakedContent)) {
                    $positionContent = $remakedContent;
                }
                self::$contentPositions[$position] = $positionContent . self::$contentPositions[$position];
                //$content = str_replace('{' . $position . '}', $positionContent, $content);
            } else {
                //trigger_error('Base view not found');
            }
        }

        return $content;
    }

    private function _loadModule($module) {

        $preContent = Factory::getObserver()->trigger('BeforeLoadModule', $module);
        $preContent = reset($preContent);
        ob_start();
        self::moduleLoad($module);
        $content = $preContent . ob_get_contents();
        ob_end_clean();
        $afterContent = Factory::getObserver()->trigger('AfterLoadModule', $module, $content);
        $afterContent = reset($afterContent);
        return $afterContent ? $afterContent : $content;
    }

    private function _formatModules($modules) {
        foreach ($modules as $module) {
            if (!empty($module->params) && isset($module->params['position'])) {
                $this->_modules[$module->params['position']][] = $module;
            }
        }
    }

    private static function moduleLoad($module) {
        $class = Loader::loadModule(trim($module->module));


        if (!empty($module->params) && isset($module->params['action'])) {
            $method = $module->params['action'];
            if ($method == '') {
                $method = 'index';
            }
            $class->setParams($module->params);
            if (method_exists($class, $method)) {
                $class->$method();
            } else {
                Factory::getURI()->redirect('404');
            }
        } elseif (method_exists($class, 'index')) {
            $class->index();
        }
    }

}

?>
