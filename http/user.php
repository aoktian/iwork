<?php
use I\DB;
use I\View;
use I\Request;
use I\AuthedRequest;
use I\Setting;

class Controller extends AuthedRequest {
    public function index() {
        return View::render('user-list', [
            'users' => DB::keyBy( "select id, email, name, department from users" ),
            'departments' => DB::keyBy( "select * from titles where caty = " . Setting::get('worktime', 'department') ),
        ]);
    }

    public function del($id, $toid) {
        if ($toid > 0 && $id != $toid) {
            $db = DB::write();
            $db->query("DELETE FROM `users` WHERE id=$id");

            $db->update('tasks')->cols(array('leader'=>$toid))->where('leader='.$id)->query();
            $db->update('tasks')->cols(array('author'=>$toid))->where('author='.$id)->query();
            $db->update('tasks')->cols(array('changer'=>$toid))->where('changer='.$id)->query();
            $db->update('tasks')->cols(array('tester'=>$toid))->where('tester='.$id)->query();

            $db->update('feedbacks')->cols(array('author'=>$toid))->where('author='.$id)->query();
            $db->update('feedbacks')->cols(array('changer'=>$toid))->where('changer='.$id)->query();
        }

        $this->index();
    }

    public function edit( ) {
        $user = Request::$auth;

        return View::render('user-reset', [
            'user' => $user,
            'departments' => DB::keyBy( "select * from titles where caty = " . Setting::get('worktime', 'department') ),
            ]);
    }

    public function update( ) {
        $me = Request::$auth;
        $id = $me->id;

        $update = array();
        $password = getgpc( 'password' );
        if ($password) {
            $update['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $update['name'] = getgpc( 'name' );
        $update['department'] = getgpc( 'department' );

        DB::write()->update('users')->cols($update)->where('id='.$id)->query();

        return Request::redirect( '/user/index' );
    }

}
