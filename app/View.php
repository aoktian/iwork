<?php
namespace I;
use \League\Plates\Engine;
class View {
    private static $templates = NULL;
    public static function templates( ) {
        if (!self::$templates) {
            self::$templates = new Engine(ROOT_DIR . '/templates');
        }
        return self::$templates;
    }

    public static function render( $file, $data = [] ) {
        self::templates( );

        echo self::$templates->render($file, $data);
        exit();
    }

    public static function addData( $data ) {
        $templates = self::templates();
        $templates->addData($data);
    }
}
