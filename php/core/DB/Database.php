<?php

class Database {

    private $_link;

    public function __construct(Factory $check) {
        $this->_connect();
    }

    private function _connect() {
        $config = Registry::get('config');
        $this->_link = mysqli_connect($config->db_host, $config->db_user, $config->db_password, $config->db_database);
        //mysql_select_db($config->db_database, $this->_link);
        mysqli_query($this->_link,'SET NAMES utf8 COLLATE utf8_general_ci');
        mysqli_query($this->_link,'SET CONNECTION_COLLATION utf8');
    }

    public function query($sql) {
        $result=mysqli_query($this->_link,$sql);
        if(mysqli_errno($this->_link)){
            die(mysqli_error($this->_link).' SQL: '.$sql);
        }
        return $result;
    }

    public function escape($str) {
        return htmlspecialchars(mysqli_escape_string($this->_link,$str));
    }
    public function last_id()
    {
        return mysqli_insert_id($this->_link);
    }
    public function free(){
        while(mysqli_more_results($this->_link)&&mysqli_next_result($this->_link)){
          mysqli_store_result($this->_link);  
        }
        mysqli_use_result($this->_link);
    }

}

?>
