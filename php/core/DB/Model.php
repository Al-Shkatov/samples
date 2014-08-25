<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Model
 *
 * @author 1
 */
class Model {

    const FETCH_ASSOC = 'ASSOC';
    const FETCH_OBJECT = 'OBJECT';
    const FETCH_ARRAY = 'ARRAY';
    const FETCH_ROW = 'ROW';
    const FETCH_FIELD = 'FIELD';
    const FETCH_ROW_OBJECT = 'OBJECT_ROW';
    const FETCH_FIELDS_ARRAY = 'FIELDS_ARRAY';
    const QUERY_FROM = false;

    /**
     *
     * @var Database
     */
    protected $_db;

    /**
     * Table name
     * @var String
     */
    protected $_name;

    /**
     * Table fields
     * @var array
     */
    protected $_fields;

    /**
     * query to execute
     * @var string
     */
    private $_sql;

    /**
     * result query
     * @var resource
     */
    private $_result;
    private $_config;
    private static $queries = array();
    protected $queryBuilded = false;
    protected $where = array();
    protected $set = array();
    protected $from = array();
    protected $joins = array();
    protected $_columns = array();
    protected $limit = '';
    protected $order = array();
    protected $group = array();
    protected $flat = array();
    protected $db_pre = '';

    public function __construct() {
        $this->_db = Factory::getDatabase();
        $this->_config = Registry::get('config');
        $this->_name = $this->_config->db_prefix . $this->_name;
        $this->db_pre = $this->_config->db_prefix;
        $this->_getFields($this->_name);
    }

    public function getName() {
        return str_replace($this->_config->db_prefix, '', $this->_name);
    }

    private function _getFields($tableName) {
        if ($tableName !== $this->db_pre) {
            if (!Cache::is_cachedPart('tbl_fields_' . $tableName)) {
                $this->_sql = 'SHOW FIELDS FROM ' . $tableName;
                $this->queryBuilded = true;
                $fields = $this->_query()->fetch();
                foreach ($fields as $field) {
                    if (isset($field['Field'])) {
                        $this->_fields[$tableName][$field['Field']] = (object) $field;
                    }
                }
                $this->_sql = "";
                Cache::putPart('tbl_fields_' . $tableName, serialize($this->_fields));
            } else {
                $this->_fields = unserialize(Cache::getPart('tbl_fields_' . $tableName));
            }
        }
    }

    public function query($sql) {
        $this->_sql = $sql;
        $this->queryBuilded = true;
        $this->_query();
        return $this;
    }

    private function _query() {
        if (defined('DEBUG') && DEBUG == true) {
            $d = debug_backtrace();
            $index = sizeof(self::$queries);
            $profiler = Factory::getProfiler();
            $observer = Factory::getObserver();
            $text = '';
            if (self::QUERY_FROM) {
                $files = array();
                foreach ($d as $trace) {
                    if (isset($trace['file'])) {
                        $fileName = explode('\\', $trace['file']);
                        $fileName = end($fileName);
                        $files[] = $fileName . '(' . $trace['line'] . ')' . (isset($trace['type']) ? $trace['type'] : '') . $trace['function'];
                    }
                }

                $text = 'Query at files: ' . implode(', ', $files) . ' <br />';
            }
            $cls = 'green';
            if (strpos($this->_sql, 'FIELDS') !== false) {
                $cls = 'red';
            }
            $text.= '<span style="color:' . $cls . '">' . $this->_sql . "</span>";

            $observer->addEvent('startQuery' . $index, array($profiler, 'start'));
            $observer->addEvent('endQuery' . $index, array($profiler, 'end'));
            $observer->trigger('startQuery' . $index, $text);

            self::$queries[] = $text;
        }
        $this->_result = $this->_db->query($this->_sql);
        if (defined('DEBUG') && DEBUG == true) {
            $observer->trigger('endQuery' . $index, $text);
        }
        return $this;
    }

    public function fetch($type = self::FETCH_ASSOC, $index = 0, $col = null) {
        $result = null;
        if (!$this->queryBuilded) {
            $this->select();
        } else {
            $this->queryBuilded = false;
        }
        switch ($type) {
            case self::FETCH_ARRAY:
                $result = $this->_getArrayList();
                break;
            case self::FETCH_ASSOC:
                $result = $this->_getAssocList();
                break;
            case self::FETCH_OBJECT:
                $result = $this->_getObjectList();
                break;
            case self::FETCH_ROW_OBJECT:
                $result = $this->_getObjectRow($index);
                break;
            case self::FETCH_ROW:
                $result = $this->_getRow($index);
                break;
            case self::FETCH_FIELD:
                $result = $this->_getField($index);
                break;
            case self::FETCH_FIELDS_ARRAY:
                $result = $this->_getFieldsArray($col);
                break;
        }

        if (isset($this->flat['key']) && isset($this->flat['value'])) {
            $copy = (array) $result;
            $tmp = array();
            foreach ($copy as $row) {
                if (isset($row[$this->flat['key']]) && isset($row[$this->flat['value']])) {
                    $tmp[$row[$this->flat['key']]] = $row[$this->flat['value']];
                }
            }
            if (sizeof($tmp)) {
                $result = $tmp;
            }
        }
        if (is_resource($this->_result)) {
            mysqli_free_result($this->_result);
        }
        return $result;
    }

    public function getAutoIncrement($tableName = null) {
        $tableName = null !== $tableName ? $this->_config->db_prefix . $tableName : $this->_name;
        $q = "SHOW TABLE STATUS LIKE '" . $tableName . "'";
        $res = $this->query($q)->fetch(Model::FETCH_ROW);
        return isset($res["Auto_increment"]) ? $res["Auto_increment"] : 0;
    }

    private function _getObjectList() {
        if ($this->_result) {
            $list = array();
            while ($row = mysqli_fetch_assoc($this->_result)) {
                $model = new stdClass();
                foreach ($row as $field => $val) {
                    $model->$field = $val;
                }
                $list[] = $model;
            }
            return $list;
        }
        return null;
    }

    private function _getObjectRow($index) {
        if ($this->_result) {
            $list = array();
            while ($row = mysqli_fetch_assoc($this->_result)) {
                $model = new stdClass();
                foreach ($row as $field => $val) {
                    $model->$field = $val;
                }
                $list[] = $model;
            }
            if (isset($list[$index])) {
                return $list[$index];
            } else {
                return null;
            }
        }

        return null;
    }

    private function _getAssocList() {
        $list = array();
        if ($this->_result) {
            while ($row = mysqli_fetch_assoc($this->_result)) {
                $list[] = $row;
            }
        }
        return $list;
    }

    private function _getArrayList() {
        if (is_resource($this->_result)) {
            $list = array();
            while ($row = mysqli_fetch_array($this->_result)) {
                $list[] = $row;
            }
            return $list;
        }
        return array();
    }

    private function _getRow($index) {

        if ($this->_result) {
            $list = array();
            while ($row = mysqli_fetch_assoc($this->_result)) {
                $list[] = $row;
            }
            if (isset($list[$index])) {
                return $list[$index];
            } else {
                return null;
            }
        }
        return null;
    }

    private function _getField($field) {
        if ($this->_result) {
            $fields = mysqli_fetch_array($this->_result);
            return $fields[$field];
        }
        return null;
    }

    private function _getFieldsArray($col) {
        if ($this->_result) {

            $fields = array();
            while ($fieldinfo = mysqli_fetch_field($this->_result)) {
                $fields[] = $fieldinfo->name;
            }

            $list = array();
            $j = 0;
            while ($row = mysqli_fetch_array($this->_result)) {
                foreach ($fields as $i => $fieldName) {
                    $list[$i][$j] = $row[$fieldName];
                }
                $j++;
            }
            return ($col !== null && is_integer($col)) ? $list[$col] : $list;
        }
        return;
    }

    protected function set($field, $value) {
        $this->set[] = $this->protectField($field) . '="' . $this->_db->escape($value) . '"';
        return $this;
    }

    protected function where($field, $value = null) {
        if (null !== $value) {

            $this->where[] = $this->protectField($field) . '="' . $this->_db->escape($value) . '"';
        } else {
            $this->where[] = $field;
        }
        return $this;
    }

    protected function in($field, $values = null) {
        if (null !== $values && sizeof($values) > 0) {
            $values = array_diff($values, array(null, ''));
            $values = implode(',', $values);
            $this->where[] = $this->protectField($field) . ' IN (' . $values . ')';
        }
        return $this;
    }

    protected function like($field, $value = null) {
        if (null !== $value) {

            $this->where[] = $this->protectField($field) . 'LIKE "' . $value . '"';
        } else {
            $this->where[] = $field;
        }
        return $this;
    }

    protected function not_like($field, $value = null) {
        if (null !== $value) {

            $this->where[] = $this->protectField($field) . 'NOT LIKE "' . $value . '"';
        } else {
            $this->where[] = $field;
        }
        return $this;
    }

    protected function limit($offset, $perpage) {
        $offset = $offset < 0 ? 0 : $offset;
        $this->limit = ' LIMIT ' . $offset . ', ' . $perpage;
        return $this;
    }

    protected function order($field, $type = 'DESC', $protect = true) {
        if ($protect) {
            $this->order[] = $this->protectField($field) . ' ' . $type;
        } else {
            $this->order[] = $field . ' ' . $type;
        }


        return $this;
    }

    protected function from($table) {
        $this->from[] = $this->_config->db_prefix . $table;
        return $this;
    }

    protected function group($field) {
        $this->group[] = $this->protectField($field);
        return $this;
    }

    protected function joinLeft($table, $on) {
        $this->joins[] = 'LEFT JOIN ' . $this->_config->db_prefix . $table . ' ON ' . $on;
        return $this;
    }

    protected function joinRight($table, $on) {
        $this->joins[] = 'RIGHT JOIN ' . $this->_config->db_prefix . $table . ' ON ' . $on;
        return $this;
    }

    protected function fields(array $fields) {
        foreach ($fields as $field) {
            $this->_columns[] = $this->protectField($field);
        }
        return $this;
    }

    /**
     * make flat array from database response
     * @example db returned array(id=>1, name=>test) flat('id','name') convert response to array(1=>test)
     * @param string $keyFieldName 
     * @param string $valueFieldName 
     */
    protected function flat($keyFieldName, $valueFieldName) {
        $this->flat['key'] = $keyFieldName;
        $this->flat['value'] = $valueFieldName;
        return $this;
    }

    protected function select($clear = true) {
        $this->createSelect();
        $this->result = $this->_query();
        if ($clear) {
            $this->clear();
        }
        $this->queryBuilded = true;
        return $this;
    }

    protected function save($data, $tableName = null) {
        $tableName = null !== $tableName ? $this->_config->db_prefix . $tableName : $this->_name;
        if (null !== $tableName && empty($this->_fields[$tableName])) {
            $this->_getFields($tableName);
        }
        foreach ($data as $field => $value) {

            if ($field != 'id' && isset($this->_fields[$tableName][$field])) {
                $this->set($field, $value);
            }
        }
        
        if ((isset($data['id']) && $data['id'] != "") || !empty($this->where)) {
            $id = isset($data['id']) ? $data['id'] : null;
            $this->createUpdate($id, $tableName);
            $this->query($this->_sql);
        } else {
            $this->createInsert($tableName);
            $this->query($this->_sql);
            $id = $this->_db->last_id();
        }
        $this->clear();
        Factory::getObserver()->trigger('purgeCache');
        return $id;
    }

    protected function delete($clear = true, $tableName = null) {
        $tableName = null !== $tableName ? $this->_config->db_prefix . $tableName : $this->_name;
        $this->createDelete($tableName);
        if ($this->_sql != '') {
            $this->result = $this->query($this->_sql);
            if ($clear) {
                $this->clear();
            }
            return $this;
        }
    }

    private function createSelect() {
        $where = implode(' AND ', $this->where);
        sizeof($this->_columns) > 0 ? $fields = implode(',', $this->_columns) : $fields = '*';
        sizeof($this->from) > 0 ? $from = implode(',', $this->from) : $from = $this->_name;
        sizeof($this->joins) > 0 ? $joins = implode(' ', $this->joins) : $joins = '';
        sizeof($this->order) > 0 ? $order = ' ORDER BY ' . implode(',', $this->order) : $order = '';
        sizeof($this->group) > 0 ? $group = ' GROUP BY ' . implode(',', $this->group) : $group = '';
        $this->_sql = 'SELECT ' . $fields . ' FROM ' . $from . ' ' . $joins . ($where ? ' WHERE ' . $where : '') . ' ' . $group . ' ' . $order . ' ' . $this->limit;
    }

    private function createUpdate($id = null, $tableName = null) {
        $where = sizeof($this->where) > 0 ? implode(' AND ', $this->where) : ' `id`=' . $id;
        $set = implode(',', $this->set);
        $this->_sql = 'UPDATE ' . $tableName . ' SET ' . $set . ' WHERE ' . $where;
    }

    private function createInsert($tableName = null) {
        $set = implode(',', $this->set);
        $this->_sql = 'INSERT INTO ' . $tableName . ' SET ' . $set;
    }

    private function createDelete($tableName) {
        $this->_sql = '';
        if (sizeof($this->where) > 0) {
            $where = implode(' AND ', $this->where);
            $this->_sql = 'DELETE FROM ' . $tableName . ' WHERE ' . $where;
        }
    }

    protected function clear() {
        $this->_sql = "";
        $this->_fields = array();
        $this->where = array();
        $this->set = array();
        $this->from = array();
        $this->joins = array();
        $this->_columns = array();
        $this->limit = '';
        $this->order = array();
        $this->group = array();
        $this->flat = array();
        $this->queryBuilded = false;
        $this->_db->free();
    }

    private function protectField($field) {
        $space = preg_split('[\s]', $field);
        $tmp = preg_split('[\.]', $space[0]);
        array_shift($space);
        $clearedField = array();
        foreach ($tmp as $string) {
            if (!preg_match('[\(|\)|\*]', $string)) {
                $string = '`' . $string . '`';
            }
            $clearedField[] = $string;
        }
        return implode('.', $clearedField) . ' ' . implode(' ', $space);
    }

}

?>
