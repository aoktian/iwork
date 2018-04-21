<?php
namespace I;

class Setting {
    public static $items = array();
    public static function get( $name, $k = NULL ) {
        if (!isset(self::$items[$name])) {
            self::$items[$name] = include(ROOT_DIR . '/setting/' . $name . '.php');
        }

        if ($k) {
            return self::$items[$name][$k];
        }

        return self::$items[$name];
    }
}
