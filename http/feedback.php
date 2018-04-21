<?php
use I\DB;
use I\View;
use I\Request;
use I\AuthedRequest;
use I\Setting;

class Controller extends AuthedRequest {
    public function edit($id) {
        return View::render('feedback-edit', [
            'feedback' => DB::write()->row("select * from feedbacks where id = $id")
        ]);
    }

    public function store( ) {
        $id = getgpc('id');
        $row = getgpc('row');

        $me = Request::$auth;
        $row['author'] = $me->id;
        $row['changer'] = $me->id;

        $now = date('Y-m-d H:i:s');
        $row['updated_at'] = $now;

        if (isset($row['message'])) {
            $row['message'] = removehost($row['message']);
        }

        $db = DB::write();
        if ($id) {
            $this->addlog( $id, $row );
            $db->update('feedbacks')->cols($row)->where('id='.$id)->query();
        } else {
            $row['created_at'] = $now;
            $id = $db->insert('feedbacks')->cols($row)->query();
        }

        return Request::redirect( '/task/show/'.$row['pid'].'#feedback.'.$id );
    }

    private function addlog( $id, $update ) {
        $old = DB::find( 'feedbacks', $id );
        $monitor = ['message'];
        $changed = array();
        foreach ($monitor as $col) {
            if (isset($update[$col]) && $update[$col] != $old->$col) {
                $changed[$col] = $old->$col;
            }
        }
        if (empty($changed)) {
            return;
        }

        if (isset($changed['author'])) {
            $row = DB::find('users', $changed[$col]);
            $changed[$col] = $row->name;
        }

        $changed['pid'] = $old->id;
        $changed['changer'] = Request::$auth->name;
        $changed['created_at'] = $update['updated_at'];

        DB::write()->insert('feedbacklogs')->cols($changed)->query();

    }

    public function show( $id ) {
        $feedback = DB::find('feedbacks', $id);
        $task = DB::find('tasks', $feedback->pid);

        return View::render('feedback-show', [
            'task' => $task,
            'feedback' => $feedback,
            'logs' => DB::write()->query("select * from feedbacklogs where pid=$id"),
            'users' => DB::keyBy( "select id, name, department from users" ),
        ]);
    }

    public function content( $id ) {
        Request::response( DB::find('feedbacks', $id)->message );
    }


}
