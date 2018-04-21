<?php
use I\DB;
use I\View;
use I\Request;
use I\Setting;

class Controller {

    public function index( ) {
        return View::render('app', ['errors' => [] ]);
    }

    public function test( ) {
        return View::render('test');
    }

    public function register( ) {
        $row = getgpc('row');
        $errors = [];
        $db = DB::write();
        if ($row) {
            if ($row['password'] != getgpc('password_confirmation')) {
                $errors[] = '密码两次输入不一致';
            }

            $email = $row['email'];
            if (!$email) {
                $errors[] = '邮箱没有填';
            }

            $exists = $db->row("select * from users where email='$email'");
            if ($exists) {
                $errors[] = '密码两次输入不一致';
            }

            if (!$errors) {
                $row['password'] = password_hash($row['password'], PASSWORD_DEFAULT);
                $id = $db->insert('users')->cols($row)->query();

                $row['id'] = $id;

                $this->onlogin( (object)$row );

            }
        }

        return View::render('register', [
            'errors' => $errors,
            'departments' => DB::keyBy( "select * from titles where caty = " . Setting::get('worktime', 'department') ),
        ]);
    }

    public function login( ) {
        $email = getgpc('email');
        if (!$email) {
            return $this->index();
        }

        $db = DB::write();
        $user = $db->select('*')->from('users')->where('email= :email')->bindValues(array('email'=>$email))->row();

        if (!$user) {
            return View::render('app', ['errors' => ['user not exists'] ]);
        }

        $password = $_POST['password'];
        if (!password_verify($password, $user->password)) {
            return View::render('app', ['errors' => ['password is wrong.'] ]);
        }

        $this->onlogin( $user );
    }

    private function onlogin( $user ) {
        $segment = Request::getsegment();
        $segment->set('authed', $user);

        return Request::redirect( '/task/index' );
    }

    public function logout( ) {
        $segment = Request::getsegment();
        $segment->set('authed', NULL);

        $this->index();
    }
}
