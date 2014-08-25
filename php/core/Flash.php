<?php
/**
 *@example Flash::set('all ok');
 * Flash::get() returned 'all ok'; 
 * Flash::get() returned null;
 */
class Flash{
    private static $storage;
    private static $instance;
    private function __construct() {
        self::$storage = Session::getInstance('__flash__');
    }
    public static function instance() {
        if(!self::$storage instanceof Session){
            self::$instance = new Flash();
        }
        return self::$instance;
    }
    /**
     *
     * @return string message was set early and unset it
     */
    public static function get(){
        $tmp = self::$storage->message;
        self::$storage->message = null;
        return $tmp;
    }
    /**
     *
     * @param string $message set message to flash
     */
    public static function set($message){
        self::$storage->message = $message;
    }
}
?>
