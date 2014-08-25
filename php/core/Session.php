<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Session
 *
 * @author Administrator
 */
class Session
{
    private static $instances=array();
    private $namespace;
    private function __construct($namespace)
    {
        if(!session_id())
        {
            session_start();
        }
        $this->namespace=$namespace;
        if(!isset($_SESSION[$this->namespace])){
            $_SESSION[$this->namespace]=array();
        }
    }
    private function __clone()
    {
        
    }
    public static function getInstance($namespace='default')
    {
        if(!isset(self::$instances[$namespace]))
        {
            self::$instances[$namespace]=new Session($namespace);
        }
        return self::$instances[$namespace];
    }
    public function set($name, $value)
    {
        $_SESSION[$this->namespace][$name]=$value;
    }
    public function get($name)
    {
        return isset($_SESSION[$this->namespace][$name])?$_SESSION[$this->namespace][$name]:null;
    }
    public function has($name)
    {
        return isset($_SESSION[$this->namespace][$name]);
    }
    public function __set($name, $value)
    {
        $this->set($name, $value);
    }
    public function __get($name)
    {
        return $this->get($name);
    }
    public function __isset($name)
    {
        return $this->has($name);
    }
            
}

?>
