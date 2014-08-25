<?php

class Factory {

    /**
     * @var Factory
     */
    private static $instance;

    /**
     *
     * @var Database 
     */
    private static $db;

    /**
     *
     * @var Document 
     */
    private static $document;

    /**
     *
     * @var Registry
     */
    private static $registry;

    /**
     *
     * @var Session
     */
    private static $session;

    /**
     *
     * @var Observer 
     */
    private static $observer;

    /**
     *
     * @var Profiler 
     */
    private static $profiler;
    
    /**
     *
     * @var URI
     */
    private static $uri;
    
    public static $admin=false;
    
    public static $ajax=false;
    
    private function __construct() {
        
    }

    private function __clone() {
        
    }

    /**
     *
     * @return Factory 
     */
    public static function getInstance() {
        if (null === self::$instance) {
            self::$instance = new Factory();
        }
        return self::$instance;
    }

    /**
     *
     * @return Document 
     */
    public static function getDocument() {
        if (null === self::$document) {
            self::$document = new Document(self::getInstance());
        }
        return self::$document;
    }

    /**
     *
     * @return Observer 
     */
    public static function getObserver() {
        if (null === self::$observer) {
            self::$observer = new Observer(self::getInstance());
        }
        return self::$observer;
    }

    /**
     *
     * @return Profiler 
     */
    public static function getProfiler() {
        if (null === self::$profiler) {
            self::$profiler = new Profiler(self::getInstance());
        }
        return self::$profiler;
    }

    /**
     *
     * @return Registry
     */
    public static function getRegistry() {
        if (null === self::$registry) {
            self::$registry = Registry::getInstance();
        }
        return self::$registry;
    }
    /**
     *
     * @return Database
     */
    public static function getDatabase()
    {
        if(null===self::$db)
        {
            self::$db=new Database(self::getInstance());
        }
        return self::$db;
    }
    /**
     *
     * @return URI
     */
    public static function getURI()
    {
        if(null===self::$uri)
        {
            self::$uri=new URI();
        }
        return self::$uri;
    }

}

?>
