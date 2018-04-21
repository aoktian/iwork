<?php
use I\DB;
use I\View;
use I\Request;
use I\AuthedRequest;
use I\Setting;

class Controller extends AuthedRequest {

    public function index( ) {
        return Request::redirect( '/task/index' );
    }

}
