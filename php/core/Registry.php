<?php

class Registry {

    /**
     *
     * @var Registry 
     */
    private static $instance;

    /**
     *
     * @return Registry
     */
    private static $data = array();

    public static function getInstance() {
        if (null === self::$instance) {
            self::$instance = new Registry();
        }
        return self::$instance;
    }

    public static function get($name) {
        return isset(self::$data[$name]) ? self::$data[$name] : null;
    }

    public static function set($name, $value) {
        self::$data[$name] = $value;
    }

    private function __construct() {
        
    }

    private function __clone() {
        
    }

    public function __set($name, $value) {
        self::$data[$name] = $value;
    }

    public function __get($name) {
        return isset(self::$data[$name]) ? self::$data[$name] : null;
    }

    public function __isset($name) {
        return isset(self::$data[$name]);
    }

}

?>
