<?php

/**
 * Document отвечает за аттрибуты конечного хтмл документа тайтлы, мета, цсс, скрипты
 *
 * @author 1
 */
class Document {

    protected $_title = "";
    protected $_meta = array();
    protected $_meta_property = array();
    protected $_styles = array();
    protected $_scripts = array();
    protected $_doctype = '<!DOCTYPE html>';

    /**
     * Разрешено загружать только фабрикой
     * @param Factory $check 
     */
    public function __construct(Factory $check) {
        ;
    }

    /**
     * Возвращает тайтл документа
     * @return string 
     */
    public function title() {
        return $this->_title;
    }

    /**
     * Установить тайтл документа
     * @param string $_title 
     */
    public function setTitle($_title) {
        $this->_title = $_title;
        return $this;
    }

    public function getTitle() {
        return $this->_title;
    }

    /**
     * Возвращает хтмл добавленных скриптов
     * @return string scripts html 
     */
    public function scripts() {
        $scripts = array();
        foreach ($this->_scripts as $pathToScript) {
            $scripts[] = '<script type="text/javascript" src="' . $pathToScript . '"></script>' . PHP_EOL;
        }
        return implode('', $scripts);
    }

    /**
     * Добавить скрипт к скриптам документа
     * @param string $path 
     */
    public function addScript($path) {
        $this->_scripts[] = $path;
        return $this;
    }

    /**
     * Возвращает хтмл добавленных стилей
     * @return string styles html 
     */
    public function styles() {
        $styles = array();
        foreach ($this->_styles as $pathToStyle) {
            $styles[] = '<link type="text/css" rel="stylesheet" href="' . $pathToStyle . '" />';
        }
        return implode('', $styles);
    }

    /**
     * Добавить таблицу стилей к стилям документа
     * @param string $path 
     */
    public function addStyle($path) {
        $this->_styles[] = $path;
        return $this;
    }

    /**
     * Возвращает доктайп документа
     * @return string 
     */
    public function doctype() {
        return $this->_doctype;
    }

    /**
     * Устанавливает доктайп документа
     * @param string doctype 
     */
    public function setDoctype($doctype) {
        $this->_doctype = $doctype;
        return $this;
    }

    /**
     * Возвращает хтмл добавленных мета тегов
     * @return string meta html 
     */
    public function meta() {
        $meta = array();
        foreach ($this->_meta as $name => $value) {
            $meta[] = '<meta name="' . $name . '" content="' . $value . '" />';
        }
        foreach ($this->_meta_property as $property => $value) {
            $meta[] = '<meta property="' . $property . '" content="' . $value . '" />';
        }

        return implode('', $meta);
    }

    public function getMeta($type = null) {
        if ($type === null) {
            return $this->_meta;
        } else {
            $meta = array();
            foreach ($this->_meta as $name => $val) {
                if ($name == $type) {
                    $meta[$name] = $val;
                }
            }
            return $meta;
        }
    }

    /**
     * Добавить мета тег
     * @param string $name
     * @param string $value 
     */
    public function addMeta($name, $value) {
        $value = trim($value);
        if (!empty($value)) {
            $this->_meta[$name] = $value;
        }
    }

    public function addMetaProperty($property, $value) {
        $this->_meta_property[$property] = $value;
    }

    public function baseurl() {
        return URI::base(true);
    }

    public function clearbaseurl() {
        return rtrim(URI::base(false), '/');
    }

    public function current_url() {
        return Factory::getURI()->current();
    }

    public function full_cur_url() {
        return Factory::getURI()->full();
    }

    public function curr_year() {
        return date('Y');
    }

    public function translate($value) {
        return _t($value);
    }

    public function mobile_class() {
        $m_detect = new MobileDetect();
        $class = '';
        if ($m_detect->isMobile() || $m_detect->isTablet()) {
            $class = 'mobile';
        }
        $class = empty($class) ? 'comp' : $class;
        return $class;
    }

    public function lang() {
        $user_lang = isset($_COOKIE['site_user_language']) ? $_COOKIE['site_user_language'] : '';
        $config = Factory::getRegistry()->config;
        $default_lang = $config->default_language;
        return empty($user_lang) ? $default_lang : $user_lang;
    }

    public function copy() {
        return $this->curr_year() . ' ' . _t('copy');
    }

    public function ajax_navigation() {
        $config = Factory::getRegistry()->config;
        return $config->ajax_navigation;
    }

}

?>
