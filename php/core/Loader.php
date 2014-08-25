<?php

class Loader {

    private static $files = array();

    public function autoLoad($className) {
        if (empty(self::$files)) {
            $this->_getFiles(ROOT_DIR . '/core');
        }
        if (!isset(self::$files[$className])) {
            trigger_error('Нема такого класса ' . $className, E_USER_ERROR);
        } else {
            include_once self::$files[$className];
        }
    }

    private function _getFiles($folder) {
        $files = scandir($folder);
        foreach ($files as $file) {
            if ($file != '.' && $file != '..') {
                if (is_dir($folder . '/' . $file)) {
                    $this->_getFiles($folder . '/' . $file);
                } else {
                    $className = str_replace('.php', '', $file);
                    self::$files[$className] = $folder . '/' . $file;
                }
            }
        }
    }

    public static function loadModule($name) {
        $admin = Factory::$admin ? '/admin' : '';
        $dir = ROOT_DIR . '/modules/';
        if (is_dir($dir . $name)) {
            if (is_file($dir . $name . $admin . '/' . $name . '.php')) {

                if (is_dir($dir . $name . '/model')) {
                    $files = scandir($dir . $name . '/model');
                    foreach ($files as $file) {
                        if ($file != '.' && $file != '..') {
                            include_once $dir . $name . '/model/' . $file;
                        }
                    }
                }
                include_once $dir . $name . $admin . '/' . $name . '.php';

                $module = new $name();

                return $module;
            } else {
                trigger_error('Нема файла модуля ' . $name, E_USER_ERROR);
            }
        } else {
            trigger_error('Нема такого модуля ' . $name, E_USER_ERROR);
        }
    }

    public static function loadModel($path) {
        $params = explode('/', $path);
        $model = end($params) . 'Model';
        
        if (is_file(ROOT_DIR . '/' . $path . '.php')) {
            include_once ROOT_DIR . '/' . $path . '.php';

            return new $model();
        } else {
            trigger_error('Model not found ' . $model, E_USER_ERROR);
        }
    }

}

?>
