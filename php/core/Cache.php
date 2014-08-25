<?php

class Cache {

    private $cacheDir = '/cache/data';
    private static $partDir = '/cache/parts/';
    private static $maxtimePart = 200;
    private $maxtime = 600; //10 минут
    private $path;

    public function __construct($route, $data = true) {
        if ($route) {
            $this->path = ROOT_DIR . $this->cacheDir . '/' . str_replace('/', '-', $route) . '.cache';
        }
    }

    public function is_cached() {
        return false;
        if (is_file($this->path)) {
            if (strtotime('now') - filemtime($this->path) < $this->maxtime) {
                return true;
            } else {
                unlink($this->path);
                return false;
            }
        }
        return false;
    }

    public function put($content) {
        $handler = fopen($this->path, 'w');
        fwrite($handler, $content);
        fclose($handler);
    }

    public function get() {
        return file_get_contents($this->path);
    }

    public static function purge($route) {

        $files = glob(ROOT_DIR . '/cache/data/*.cache');
        $files = array_merge($files, glob(ROOT_DIR . '/cache/parts/*.cache'));
        foreach ($files as $file) {
            unlink($file);
        }
    }

    public static function putPart($part, $value) {
        $handler = fopen(ROOT_DIR . self::$partDir . $part . '.cache', 'w');
        fwrite($handler, $value);
        fclose($handler);
    }

    public static function getPart($part) {
        return file_get_contents(ROOT_DIR . self::$partDir . $part . '.cache');
    }

    public static function is_cachedPart($part) {
        $filename = ROOT_DIR . self::$partDir . $part . '.cache';
        if (is_file($filename)) {
            if (strtotime('now') - filemtime($filename) < self::$maxtimePart) {
                return true;
            } else {
                unlink($filename);
                return false;
            }
        }
        return false;
    }

    public static function purgePart($part) {
        $files = glob(ROOT_DIR . self::$partDir . $part . '*.cache');
        foreach ($files as $filename) {
            if (is_file($filename)) {
                unlink($filename);
            }
        }
    }

}

?>
