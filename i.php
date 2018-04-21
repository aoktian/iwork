<?php
define('ROOT_DIR', dirname(__FILE__));
define('PUBLIC_DIR', ROOT_DIR . '/public' );

require ROOT_DIR . '/vendor/autoload.php';
use I\Setting;

date_default_timezone_set(Setting::get('app', 'timezone'));


