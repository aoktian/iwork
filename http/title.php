<?php
use I\DB;
use I\View;
use I\Request;
use I\AuthedRequest;
use I\Setting;

class Controller extends AuthedRequest {
    public function index() {
        return View::render('title-list', [
            'titles' => DB::write()->query('select * from titles order by id')
            ]);
    }

    public function store( ) {
        $id = getgpc('id');
        $db = DB::write();

        $row = getgpc('row');

        if (!$row['caty']) {
            return Request::redirect( '/title/index' );
        }

        $name = trim($row['name']);
        if (!$name) {
            return Request::redirect( '/title/index' );
        }

        $db = DB::write();
        if ($id) {
            $title = $db->row('select * from titles where id = ' . $id);
            if ($title->locked) {
                unset($row['name']);
            }
            $db->update('titles')->cols($row)->where('id='.$id)->query();
        } else {
            $id = $db->insert('titles')->cols($row)->query();
        }

        return Request::redirect( '/title/index' );
    }

    public function del($id) {
        $title = $db->row('select * from titles where id = ' . $id);

        if ($title->locked) {
            return Request::redirect( '/title/index' );
        }

        return View::render('title-del', [
            'title' => $title,
            'titles' => DB::keyBy('select * from titles order by id')
            ]);
    }

    public function destroy( ) {
        $id = getgpc( 'id' );
        $title = $db->row('select * from titles where id = ' . $id);
        if ( $title->locked || $title->id == $toid ) {
            return Request::redirect( '/title/index' );
        }

        $toid = getgpc( 'toid' );
        $totitle = $db->row('select * from titles where id = ' . $toid);

        if ($title->caty != $totitle->caty) {
            return Request::redirect( '/title/index' );
        }

        if ($title->caty == Setting::get('worktime', 'caty')) {
            $db->update('tasks')->cols(['caty' => $toid])->where('caty='.$id)->query();
        } elseif ($title->caty == Setting::get('worktime', 'department')) {
            $db->update('users')->cols(['department' => $toid])->where('department='.$id)->query();
            $db->update('tasks')->cols(['department' => $toid])->where('department='.$id)->query();
        } else {
            return Request::redirect( '/title/index' );
        }

        $db->query("DELETE FROM `titles` WHERE id=$id");

        return Request::redirect( '/title/index' );
    }
}
