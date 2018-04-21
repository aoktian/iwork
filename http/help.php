<?php
use I\DB;
use I\View;
use I\Request;
use I\Setting;

class Controller {

    public function index( ) {
        return View::render('how-to-use');
    }
}
