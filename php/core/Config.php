<?php
class Config {
    private $_data=array();
    public function __construct()
    {
        $data=include_once dirname(__FILE__).'/../configuration.php';
        foreach($data as $key=>$val)
        {
            $this->$key=$val;
        }
    }
    public function __set($name, $value) {
        $this->_data[$name]=$value;
    }
    public function __get($name) {
        return isset($this->_data[$name])?$this->_data[$name]:null;
    }
}

?>
