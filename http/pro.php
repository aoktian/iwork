<?php
use I\DB;
use I\View;
use I\Request;
use I\AuthedRequest;
use I\Setting;

class Controller extends AuthedRequest {
    public function index() {
        return View::render('pro-list', [
            'pros' => DB::write()->query("select * from pros")
            ]);
    }

    public function store( ) {
        $id = getgpc('id');
        $row = getgpc('row');

        $db = DB::write();
        $now = date('Y-m-d H:i:s');
        $row['updated_at'] = $now;
        if ($id) {
            $db->update('pros')->cols($row)->where('id='.$id)->query();
        } else {
            $row['created_at'] = $now;
            $id = $db->insert('pros')->cols($row)->query();
        }

        return Request::redirect( '/pro/index' );
    }

    public function destroy($id) {
        $db = DB::write();

        $row = $db->row('select count(*) as num from tasks where pro=' . $id);
        if ($row->num > 0) {
            return View::render('error', ['msg' => '本项目下还有任务', 'backurl' => '/pro/index']);
        }

        $row = $db->row('select count(*) as num from tags where pro=' . $id);
        if ($row->num > 0) {
            return View::render('error', ['msg' => '本项目下还有版本', 'backurl' => '/pro/index']);
        }

        $db->query("DELETE FROM `pros` WHERE id=$id");

        return Request::redirect( '/pro/index' );
    }
}
