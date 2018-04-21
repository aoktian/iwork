<?php
namespace I;
class AuthedRequest {

    public function __construct( ) {
        Request::checkAuth( );
    }

}
