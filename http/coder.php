<?php
use I\DB;
use I\View;
use I\Request;
use I\AuthedRequest;
use I\Setting;

//程序员的生产力
class Controller extends AuthedRequest {
    public function index() {
        return View::render('coder-index', [
            't_start_show' => '',
            't_end_show' => ''
        ]);
    }

    public function intime( ) {
        $t_start_show = getgpc('t_start');
        $t_end_show = getgpc('t_end');
        $t_start = $t_start_show . ' 00:00:00';
        $t_end = $t_end_show . ' 23:59:59';

        $db = DB::write();

        return View::render('coder-index', [
            't_start_show' => $t_start_show,
            't_end_show' => $t_end_show,
            'users' => DB::keyBy( "select id, name, department from users order by department" ),
            'coders' => $this->coders( $t_start, $t_end )
        ]);

    }

    private function coders( $t_start, $t_end ) {
        $db = DB::write();
        $sql = "select leader, caty, count(*) as num from tasks where updated_at>='$t_start' and updated_at<='$t_end' group by leader, caty";
        $a = $db->query($sql);

        $default = array(
            10 => 0, 20 => 0, 30 => 0
        );

        $data = array();
        foreach ($a as $row) {
            if (!isset($data[$row->leader])) {
                $data[$row->leader] = $default;
            }

            $data[$row->leader][$row->caty] = $row->num;
        }

        return $data;
    }

}
