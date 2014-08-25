<?php

class URI {
    private static $basePath = '';
    private static $route = null;

    public static function base($flag = false) {
        if (self::$basePath == '' || $flag) {
            $proto = 'http://';
            $host = $_SERVER['HTTP_HOST'];
            //$port = $_SERVER['SERVER_PORT'] != '80' ? ':' . $_SERVER['SERVER_PORT'] : '';
            $basePath = str_replace('admin/', '', str_replace('index.php', '', $_SERVER['SCRIPT_NAME']));
            if (!$flag) {
                self::$basePath = $basePath;
            } else {
                return $proto . $host . $basePath;
            }
        }
        return self::$basePath;
    }

    public function host() {
        return 'http://' . $_SERVER["HTTP_HOST"];
    }

    public function self() {
        return $_SERVER['PHP_SELF'];
    }

    public function current($get_params = null) {
        $current = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $_SERVER['PHP_SELF'];
        $current = explode('?', $current);
        $current = reset($current);
        return str_replace('index.php/', '', $current);
    }

    public function full() {
        return $_SERVER['REQUEST_URI'];
    }

    public function params($index = null) {
        $params = explode('/', $this->route());
        return $params;
    }

    public function route($get_params = null, $remove_params = false) {

        if (self::$route == null) {
            self::$route = preg_replace('[^' . self::base() . ']i', '', $this->current());
        }
        $route = self::$route;
        if ($remove_params) {
            $route_arr = explode('/', $route);
            return isset($route_arr[0]) ? $route_arr[0] : '';
        }
        if (!empty($get_params)) {
            foreach ($get_params as $key => $value) {
                if (empty($value)) {
                    $route = preg_replace('/\/\//', '/:' . $key . '/', $route, 1);
                } else {
                    $route = preg_replace('/' . $value . '/', ':' . $key, $route, 1);
                }
            }
        }
        return $route;
    }

    public function redirect($route) {
        if (headers_sent()) {
            echo '<script type="text/javascript">window.location.href="' . self::base() . $route . '"</script>';
        } else {
            header('Location: ' . self::base() . $route);
        }
    }

    public function refresh($params = null) {
        $url = $this->route();
        $url = ($url == 'index') ? '' : $url;
        if (!empty($params)) {
            $url.='?';
            $new_params = array();
            foreach ($params as $key => $val) {
                $new_params[] = $key . '=' . $val;
            }
            $new_params = implode('&', $new_params);
            $url.=$new_params;
        }
        $this->redirect($url);
    }

    public function setRoute($route) {
        self::$route = $route;
    }

    public function formLinkFromGetParams($url, $request_params) {
        $res = $url;
        $request_arr = array();
        if (!empty($request_params)) {
            $res.='?';
            foreach ($request_params as $key => $value) {
                $request_arr[] = $key . '=' . $value;
            }
            $res.= implode('&', $request_arr);
        }
        return $res;
    }

}

?>
