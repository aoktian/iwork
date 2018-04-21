<?php
use I\DB;

class Controller {

    public function password( ) {
        echo $this->generate_password( ) . "\n";
    }

    private function generate_password( $length = 8 ) {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

        $password = '';
        for ( $i = 0; $i < $length; $i++ ) {
            $password .= $chars[ mt_rand(0, strlen($chars) - 1) ];
        }

        return $password;
    }

    public function add( ) {

        $name = getgpc('name');
        $email = getgpc('email');

        if (!$name || !$email) {
            echo 'args error.';
            return;
        }

        $password = $this->generate_password();
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $id = DB::write()->insert('users')->cols(array(
            'name'=> $name,
            'email'=> $email,
            'password'=> $hash
        ))->query();

        echo sprintf('%s %s %s', $name, $email, $password);

    }

    public function newpass( ) {
        $email = getgpc('email');
        $user = DB::write()->row("select * from users where email='$email'");
        $this->changed( $user );
    }

    public function reset( ) {
        $users = DB::write()->query("select email from users");
        foreach ($users as $user) {
            $this->changed( $user );
        }
    }

    private function changed( $user ) {
        $password = $this->generate_password();
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $row = array();
        $row['password'] = $hash;
        DB::write()->update('users')->cols($row)->where("id={$user->id}")->query();
        echo $user->name . "\t" . $user->email . "\t" . $password . "\n";
    }
}
