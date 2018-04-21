<?php
namespace I;
class Request {

    public static function is( $name ) {
        return '/'.CONTROLLER . '/' . ACTION == $name;
    }

    public static function isctl( $name ) {
        return CONTROLLER == $name;
    }

    public static function redirect( $url, $delay = 0 ) {
        View::render( 'redirect', ['delay' => $delay, 'url' => $url ] );
        exit();
    }

    public static $session = NULL;
    public static $segment = NULL;
    public static function getsession( ) {
        if (!self::$session) {
            $session_factory = new \Aura\Session\SessionFactory;
            self::$session = $session_factory->newInstance($_COOKIE);
        }

        return self::$session;
    }

    public static function getsegment( ) {
        if (!self::$segment) {
            self::getsession();
            self::$segment = self::$session->getSegment('iGmae');
        }

        return self::$segment;
    }

    public static $auth = NULL;
    public static function checkAuth( ) {
        // todo check session
        $segment = self::getsegment();
        $auth = $segment->get('authed');
        if (!$auth) {
            return Request::redirect( '/auth/index' );
        }

        $id = $auth->id;
        $user = DB::write()->row("select * from users where id = $id");

        if (!$user || $user->password != $auth->password) {
            if (self::isajax()) {
                return Request::response( '/auth/index' );
            } else {
                return Request::redirect( '/auth/index' );
            }
        }

        self::$auth = $auth;

        self::checkpermission( );

    }

    public static function checkpermission( ) {
        if (false) {
            return Request::redirect( '/auth/index' );
        }
    }

    public static function response( $s ) {
        echo $s;exit();
    }

    public static function json( $a ) {
        echo json_encode($a);exit();
    }

    public static function isajax( ) {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])=='xmlhttprequest';
    }

}
