<?php
use I\DB;
use I\View;
use I\Request;
use I\AuthedRequest;
use I\Setting;

class Controller extends AuthedRequest {
    public function index() {
        $this->_page();
        return View::render('tag-list');
    }

    public function page() {
        $this->_page();
        return View::render('tag-list-content');
    }

    public function _page() {
        $sqlcount = 'select count(*) as num from tags';
        $sql = 'select * from tags';

        $pro = getgpc( 'pro', 0 );
        if ($pro) {
            $wheresql = ' where pro=' . $pro;
            $sqlcount .= $wheresql;
            $sql .= $wheresql;
        }

        $db = DB::write();

        $count_row = $db->row($sqlcount);
        $totalnum = $count_row->num;
        $curpage = getgpc( 'page', 1 );
        $perpage = 20;
        $offset = page_get_start($curpage, $perpage, $totalnum);

        $sql .= ' order by id desc';
        $sql .= " limit $perpage offset $offset";

        View::addData([
            'tags' => DB::keyBy( $sql ),
            'pros' => DB::write()->query("select * from pros"),
            'pro' => $pro,
            'totalnum' => $totalnum,
            'curpage' => $curpage,
            'perpage' => $perpage
        ]);
    }

    public function store($id = 0) {
        $row = getgpc('row');

        $db = DB::write();
        if ($id) {
            $tag = $db->row("select * from tags where id = $id");

            $db->update('tags')->cols($row)->where('id='.$id)->query();

            if ($tag->pro != $row['pro']) {
                $db->update('tasks')->cols(['pro' => $tag->pro])->where('tag='.$id)->query();
            }
        } else {
            $id = $db->insert('tags')->cols($row)->query();
        }

        return $this->_index();
    }

    private function _index( ) {
        $pro = getgpc( 'pro', 0 );
        $curpage = getgpc( 'page', 1 );

        return Request::redirect( '/tag/index?pro=' . $pro . '&page=' . $curpage );
    }

    public function destroy($id) {
        $db = DB::write();

        $row = $db->row('select count(*) as num from tasks where tag=' . $id);
        if ($row->num > 0) {
            return View::render('error', ['msg' => '本版本下还有任务', 'backurl' => '/pro/index']);
        }

        $db->query("DELETE FROM `tags` WHERE id=$id");

        return $this->_index();
    }

    public function stats( ) {
        $s_department = array();
        $departments = DB::keyBy( "select * from titles where caty = " . Setting::get('worktime', 'department') );
        $status = Setting::get('worktime', 'status');
        $default_status = array();
        foreach ($status as $status_id => $value) {
            $default_status[$status_id] = 0;
        }
        $default_status['new'] = 0;

        $t_start = getgpc('start') . ' 00:00:00';
        $t_end = getgpc('end') . ' 00:00:00';

        $db = DB::write();

        $a = $db->query("select count(*) as num, department as tname, status from tasks where updated_at>='$t_start' and updated_at<='$t_end' group by department, status");

        $s_all = $default_status;
        foreach ($a as $row) {
            $s_all[$row->status] += $row->num;
        }

        $aa = $db->query("select count(*) as num, department as tname from tasks where created_at>='$t_start' and updated_at<='$t_end' and status!=90 and status!=99 group by department");
        foreach ($aa as $row) {
            $s_all['new'] += $row->num;
        }

        $s_department = $this->getdata( $a, $aa, $default_status );

        $a = $db->query("select count(*) as num, leader as tname, status from tasks where updated_at>='$t_start' and updated_at<='$t_end' group by leader, status order by department");
        $aa = $db->query("select count(*) as num, leader as tname from tasks where created_at>='$t_start' and created_at<='$t_end' and status !=90 and status!=99 group by leader");
        $s_leader = $this->getdata( $a, $aa, $default_status );

        $a = $db->query("select count(*) as num, pro as tname, status from tasks where updated_at>='$t_start' and updated_at<='$t_end' group by pro, status");
        $aa = $db->query("select count(*) as num, pro as tname from tasks where created_at>='$t_start' and created_at<='$t_end' and status !=90 and status!=99 group by pro");
        $s_pro = $this->getdata( $a, $aa, $default_status );

        $tag = (object) array();
        $tag->name = '时间统计';
        $tag->t_start = $t_start;
        $tag->t_end = $t_end;

        return View::render('tag-statistics', [
            'tag' => $tag,
            'users' => DB::keyBy( "select id, name, department from users" ),
            'departments' => $departments,
            'pros' => DB::keyBy( "select * from pros" ),
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

    public function vvv( ) {
        $t_start = getgpc('t_start');
        $t_end = getgpc('t_end');
        $wheres = getgpc('row');
        $t = ['', ''];

        $db = DB::write();

        $t_start_show = '';
        $t_end_show = '';

        $sqlwhere = array();

        if ($t_end && $t_start) {
            $t_start_show = $t_start;
            $t_end_show = $t_end;
            $t_start = $t_start . ' 00:00:00';
            $t_end = $t_end . ' 23:59:59';

            $sqlwhere[] = "updated_at>='$t_start'";
            $sqlwhere[] = "updated_at<='$t_end'";
        } else {
            if (!$wheres) {
                $t_start_show = date('Y-m-d', time() - 86400);
                $t_end_show = date('Y-m-d');

                $t_start = $t_start_show . ' 00:00:00';
                $t_end = $t_end_show . ' 23:59:59';

                $sqlwhere[] = "updated_at>='$t_start'";
                $sqlwhere[] = "updated_at<='$t_end'";
            }
        }

        if (!$wheres) {
            $wheres = array();
        }
        foreach ($wheres as $where => $val) {
            if ($val > 0 ) {
                $sqlwhere[] = "$where='$val'";
            }
        }

        $sql = 'select * from tasks';
        if ($sqlwhere) {
            $sql .= ' where ' . implode(' and ', $sqlwhere);
        }
        $sql .= ' order by department, caty, status';

        $rows = $db->query($sql);

        $catys = DB::keyBy( "select * from titles where caty = " . Setting::get('worktime', 'caty') );
        $users = DB::keyBy( "select id, name, department from users" );


        $pros = DB::keyBy( "select * from pros" );
        $tags = DB::keyBy( "select * from tags order by id desc" );
        $departments = DB::keyBy( "select * from titles where caty = " . Setting::get('worktime', 'department') );

        $a = array();
        foreach ($rows as $row) {
            $row = $row;
            $titles = explode('】', $row->title);
            if (count($titles) <= 1) {
                continue;
            }

            $type = $catys[$row->caty]->name;

            $module = str_replace('【', '', $titles[0]);
            if (isset($titles[2])) {
                $caty = str_replace('【', '', $titles[1]);
            } else {
                $caty = $catys[$row->caty]->name;
            }

            $status = Setting::get('worktime', 'status');

            $a[] = [
                'module' => $module,
                'type' => $type,
                'caty' => $caty,
                'leader' => $users[$row->leader]->name,
                'author' => $users[$row->author]->name,
                'tester' => $row->tester ? $users[$row->tester]->name : 'abc',
                'status' => $status[$row->status],
                'pro' => $pros[$row->pro]->name,
                'tag' => $tags[$row->tag]->name
            ];
        }

        return View::render('tag-bug', [
            'pros' => $pros,
            'tags' => $tags,
            'catys' => $catys,
            'departments' => $departments,
            'users' => $users,
            'a' => $a,
            'wheres' => $wheres,
            't_start' => $t_start,
            't_end' => $t_end
        ]);
    }
}
