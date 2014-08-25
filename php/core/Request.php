<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Request
 *
 * @author Administrator
 */
class Request {

    /**
     *
     * @var URI
     */
    private $_uri;

    /**
     *
     * @var array 
     */
    private $_params;

    public function __construct() {
        $this->_uri = new URI();
        $this->_params = array_merge($_GET, $_POST, $this->_uri->params());
    }

    public function setParam($name, $value) {
        $this->_params[$name] = $value;
    }

    public function getParam($name) {
        return isset($this->_params[$name]) ? $this->_params[$name] : null;
    }

    public function getParams() {
        return $this->_params;
    }

    public function hasParam($name) {
        return isset($this->_params[$name]);
    }

}

?>
