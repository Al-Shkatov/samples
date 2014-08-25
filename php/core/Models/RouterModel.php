<?php

class RouterModel extends Model {

    protected $_name = 'routes';
    protected $_layout = '';
    protected $_route = '';

    public function __construct() {
        parent::__construct();
    }

    public function getModulesByRoute($route) {
        $paramsRoute = Factory::getURI()->params();
        $this->_route = $checkRoute = $this->_route ? $this->_route : $this->_checkRoute($paramsRoute);
        $module_type = Factory::$admin ? 'admin' : 'user';
        $modules = array();

        if (!empty($checkRoute)) {
            $modules = array();
            $modules = $this->from('routes as r')
                    ->joinLeft('routes_to_modules as r2m', 'r2m.route_id=r.id')
                    ->joinLeft('modules as m', 'm.id=r2m.module_id OR (m.broadcast=1 AND m.type="' . $module_type . '")')
                    ->where('m.status', 1)
                    ->where('r.path = "' . $checkRoute . '" OR r.path LIKE("' . $checkRoute . ':%")')
                    ->group('m.id')
                    ->order('m.ordering', 'ASC')
                    ->select()
                    ->fetch(Model::FETCH_OBJECT);
        }

        $mods = array();
        $params = array();
        $request = Factory::getRegistry()->request;
        
        if ($checkRoute != $route) {
            $paramsCheck = explode('/', $checkRoute);
            for ($i = 0; $i < sizeof($paramsCheck); $i++) {
                if (isset($paramsRoute[$i]) && $paramsRoute[$i] != $paramsCheck[$i]) {
                    $name = str_replace(':', '', $paramsCheck[$i]);
                    $value = $paramsRoute[$i];
                    $params[$name] = $value;
                    $request->setParam($name, $value);
                }
            }
        }
        foreach ($modules as $module) {
            $tmp = new stdClass();
            $tmp->module = $module->module;
            $tmp->params = array_merge($params, parseParams($module->params));
            $mods[] = $tmp;
        }
        return $mods;
    }

    public function getLayoutByRoute($route) {

        if ($this->_layout == "") {
            $this->_route = $checkRoute = $this->_route ? $this->_route : $this->_checkRoute(Factory::getURI()->params());
            $checkRoute = empty($checkRoute) ? $route : $checkRoute;

            $this->_layout = $this->where('path', $checkRoute)
                    ->fields(array('layout'))
                    ->select()
                    ->fetch(Model::FETCH_FIELD);
        }
        if (Factory::$ajax) {
            return $this->_layout . '_ajax';
        } else {
            return $this->_layout;
        }
    }

    private function _checkRoute($params,$check = false) {
        $path = implode('/', $params);
        if(!$check && null!==($route = $this->_checkStatic($path))){
            return $route;
        }
        if($path{strlen($path)-1} === '/'){
            $path = substr($path,0,strlen($path)-1);
        }
        $route = $this->fields(array('path'))
                ->where('path="' . $path . '" OR path LIKE("' . $path . '/:%")')
                ->order('LENGTH(path)')
                ->select()
                ->fetch(Model::FETCH_FIELD,0);

        if($path==$route){
            return $route;
        }
        array_pop($params);
        if ($route == null && sizeof($params) > 0) {
            $route = $this->_checkRoute($params);
        } elseif ($route == null && sizeof($params) == 0) {
            return null;
        }
        return $route;
    }
    private function _checkStatic($route){
        if($route{strlen($route)-1} === '/'){
            $route = substr($route,0,strlen($route)-1);
        }
        return $this->fields(array('path'))
            ->where('path="' . $route . '"')
            ->select()
            ->fetch(Model::FETCH_FIELD,0);
    }

    public function saveRoute($data) {
        return $this->save($data);
    }

    public function getRoute($id) {
        return $this->where('id', (int) $id)->select()->fetch(Model::FETCH_ROW);
    }

}

?>
