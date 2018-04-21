<?php
include '../i.php';

$uri = parse_url($_SERVER["REQUEST_URI"]);

$path = $uri['path'];
$paths = explode('/', $path);
$ctl = isset($paths[1]) && $paths[1] ? $paths[1] : 'index';
$act = isset($paths[2]) && $paths[2] ? $paths[2] : 'index';
unset($paths[0]);
unset($paths[1]);
unset($paths[2]);

define( 'CONTROLLER', $ctl );
define( 'ACTION', $act );
define( 'PATH', '/' . $ctl . '/' . $act );

$http = ROOT_DIR . '/http/' . $ctl . '.php';
if (!file_exists($http)) {
    exit($ctl . ' controller is not exists.');
}
include($http);
$controller = new Controller( );
if (method_exists($controller, $act)) {
    $controller->$act( ...$paths );
}


