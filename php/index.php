<?php
define('DEBUG', 0);//$_SERVER['REMOTE_ADDR']=='194.44.30.103');


if (DEBUG) {
    register_shutdown_function('err');
    error_reporting(E_ALL & E_STRICT & E_USER_ERROR);
    ini_set('display_errors', 1);
    set_error_handler('catch_error');
}else{
    error_reporting(0);
}
define('ROOT_DIR', dirname(__FILE__));
require_once './core/Loader.php';
spl_autoload_register(array(new Loader(), 'autoload'));

$app = new Application();
$app->run();

function catch_error($errno, $errstr, $errfile, $errline) {
    static $css =0;
    if(!$css){
        $css++;
        echo
        '<style type="text/css">
            table.error{border:1px solid red;font:monospace}
            table.error td,table.error th{padding: 3px;border: 1px solid}
        </style>';
    }
$err = <<<ERROR
<table class="error" border="1" cellpadding="2">
    <tr>
        <th>No</th><th>Text</th><th>File</th><th>Line</th>
    </tr>
    <tr>
        <td>$errno</td><td>$errstr</td><td>$errfile</td><td>$errline</td>
    </tr>
</table>
ERROR;

    echo $err;
}

function err(){
    $err = error_get_last();
    if($err){
        _dbg(error_get_last());
    }
}
