<?php
use I\DB;
use I\View;
use I\Request;
use I\AuthedRequest;
use I\Setting;

//程序员的生产力
class Controller extends AuthedRequest {
    public function index() {
        $this->adddata();
        return View::render('stats-index');
    }

    public function adddata() {
        $templates = View::templates();
        $templates->addData([
            'pros' => DB::keyBy( "select * from pros" ),
            'tags' => DB::keyBy( "select id, name, pro from tags order by id desc" ),
        ]);
    }

    public function tag( ) {
        $pro = getgpc('pro');
        $tag = getgpc('tag');
        $templates = View::templates();
        $templates->addData([
            'pro_slt' => $pro,
            'tag_slt' => $tag
        ]);


        return $this->where( "tag='$tag'" );
    }

    public function intime( ) {
        $t_start_show = getgpc('t_start');
        $t_end_show = getgpc('t_end');

        $t_start = $t_start_show . ' 00:00:00';
        $t_end = $t_end_show . ' 23:59:59';

        $templates = View::templates();
        $templates->addData([
            't_start_show' => $t_start_show,
            't_end_show' => $t_end_show
        ]);

        return $this->where( "updated_at>='$t_start' and updated_at<='$t_end'" );
    }

    private function where( $where ) {
        $this->adddata();

        $s_department = array();
        $departments = DB::keyBy( "select * from titles where caty = " . Setting::get('worktime', 'department') );
        $status = Setting::get('worktime', 'status');
        $default_status = array();
        foreach ($status as $status_id => $value) {
            $default_status[$status_id] = 0;
        }
        $default_status['new'] = 0;

        $t_start_show = getgpc('t_start');
        $t_end_show = getgpc('t_end');
        $t_start = $t_start_show . ' 00:00:00';
        $t_end = $t_end_show . ' 23:59:59';

        $db = DB::write();

        $a = $db->query("select count(*) as num, department as tname, status from tasks where $where group by department, status");

        $s_all = $default_status;
        foreach ($a as $row) {
            $s_all[$row->status] += $row->num;
        }

        $aa = $db->query("select count(*) as num, department as tname from tasks where $where and status!=90 and status!=99 group by department");
        foreach ($aa as $row) {
            $s_all['new'] += $row->num;
        }

        $s_department = $this->getdata( $a, $aa, $default_status );

        $a = $db->query("select count(*) as num, leader as tname, status from tasks where $where group by leader, status order by department");
        $aa = $db->query("select count(*) as num, leader as tname from tasks where $where and status !=90 and status!=99 group by leader");
        $s_leader = $this->getdata( $a, $aa, $default_status );

        $a = $db->query("select count(*) as num, pro as tname, status from tasks where $where group by pro, status");
        $aa = $db->query("select count(*) as num, pro as tname from tasks where $where and status !=90 and status!=99 group by pro");
        $s_pro = $this->getdata( $a, $aa, $default_status );

        return View::render('stats-index', [
            'users' => DB::keyBy( "select id, name, department from users order by department" ),
            'departments' => $departments,
            's_all' => $s_all,
            's_department' => $s_department,
            's_pro' => $s_pro,
            's_leader' => $s_leader
        ]);

    }

    private function getdata( $a, $aa, $default_status ) {
        $rtn = array();
        foreach ($a as $row) {
            if (!isset($rtn[$row->tname])) {
                $rtn[$row->tname] = $default_status;
            }
            $rtn[$row->tname][$row->status] = $row->num;
        }
        foreach ($aa as $row) {
            $rtn[$row->tname]['new'] = $row->num;
        }
        return $rtn;
    }

}
