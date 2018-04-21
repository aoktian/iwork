<?php
use I\DB;
use I\View;
use I\Request;
use I\AuthedRequest;
use I\Setting;

class Controller extends AuthedRequest {
    public function index( ) {
        return $this->formatlist([]);
    }

    public function ido() {
        return $this->formatlist(['leader' => Request::$auth->id]);
    }

    public function checks() {
        return $this->formatlist(['caty' => Setting::get('worktime', 'check')]);
    }

    public function icommit() {
        return $this->formatlist(['author' => Request::$auth->id]);
    }

    public function itest() {
        return $this->formatlist( ['tester' => Request::$auth->id]);
    }

    private function getSortedusers( ) {
        $users = DB::keyBy( "select id, name, department from users" );
        $sortusers = array();
        foreach ($users as $k => $v) {
            $sortusers[$k] = iconv('UTF-8', 'GBK', $v->name);
        }
        asort($sortusers);
        $sortedusers = array();
        foreach ($sortusers as $k => $name) {
            $sortedusers[$k] = $users[$k];
        }
        return $sortedusers;
    }

    private function formatlist($searchargs)
    {
        $db = DB::write();
        $ids = getgpc('ids');
        if ($ids) {
            $updates = array();
            foreach (getgpc('changeto') as $key => $value) {
                if ($value > 0) {
                    $updates[$key] = $value;
                }
            }

            if (isset($updates['tag'])) {
                $tag = $db->row('select * from tags where id = ' . $updates['tag']);
                $updates['pro'] = $tag->pro;
            }
            if (isset($updates['leader'])) {
                $leader = $db->row('select * from users where id = ' . $updates['leader']);
                $updates['department'] = $leader->department;
            }

            if ($updates) {
                $updates['updated_at'] = date('Y-m-d H:i:s');
                $db->update('tasks')->cols($updates)->where('ID in('.implode(',', $ids).')')->query();
            }
        }

        $options = array();
        $search = getgpc('search');
        if (!$search) {
            $search = array();
        }

        $search = array_merge($search, $searchargs);

        $where = array();
        foreach ($search as $key => $value) {
            if ($value > 0) {
                $options[$key] = $value;
                $where[] = $key . '="' . $value . '"';
            }
        }

        $title = getgpc('title');
        if ($title) {
            $options['title'] = $title;
            $where[] = 'title like "%' . $title . '%"';
        }

        $sqlcount = 'select count(*) as num from tasks';
        $sql = 'select * from tasks';
        if (count($where)) {
            $wheresql = implode(' and ', $where);
            $sqlcount .= ' where ' . $wheresql;
            $sql .= ' where ' . $wheresql;
        }

        $count_row = $db->row($sqlcount);
        $totalnum = $count_row->num;
        $curpage = getgpc( 'page', 1 );
        $perpage = 20;
        $offset = page_get_start($curpage, $perpage, $totalnum);

        $sql .= ' order by status';
        $orderby = getgpc('orderby');
        if ($orderby) {
            if ('updated_at' == $orderby) {
                $sql .= ', updated_at desc';
            } elseif ('deadline' == $orderby) {
                $sql .= ', deadline';
            }
        } else {
            $sql .= ', updated_at desc';
            $orderby = '';
        }
        $sql .= ', tag';
        $sql .= ', priority desc';

        $sql .= " limit $perpage offset $offset";
        $tasks = $db->query($sql);

        $tpl = 'task-list';
        if (Request::isajax()) {
            $tpl = 'task-list-content';
        }

        View::addData([
            'tasks' => $tasks,
            'pros' => DB::keyBy( "select * from pros" ),
            'users' => $this->getSortedusers(),
            'tags' => DB::keyBy( "select id, name, pro from tags order by id desc" ),
            'status' => Setting::get('worktime', 'status'),
            'prioritys' => Setting::get('worktime', 'priority'),
            'catys' => DB::keyBy( "select * from titles where caty = " . Setting::get('worktime', 'caty') ),
            'departments' => DB::keyBy( "select * from titles where caty = " . Setting::get('worktime', 'department') ),
            'options' => $options,
            'orderby' => $orderby,
            'totalnum' => $totalnum,
            'curpage' => $curpage,
            'perpage' => $perpage
        ]);

        return View::render($tpl);
    }

    public function create( $id = 0, $related_id = 0 ) {
        $db = DB::write();
        if ($id) {
            $task = $db->row("SELECT * FROM `tasks` WHERE id='$id'");
            $related_id = $task->related;
        } else {
            $task = NULL;
        }

        if ($related_id) {
            $related = $db->row("SELECT * FROM `tasks` WHERE id='$related_id'");
        } else {
            $related = NULL;
        }

        return View::render('task-commit', [
            'task' => $task,
            'related' => $related,
            'pros' => DB::keyBy( "select * from pros" ),
            'users' => $this->getSortedusers(),
            'tags' => DB::keyBy( "select id, name, pro from tags order by id desc" ),
            'status' => Setting::get('worktime', 'status'),
            'catys' => DB::keyBy( "select * from titles where caty = " . Setting::get('worktime', 'caty') ),
            'departments' => DB::keyBy( "select * from titles where caty = " . Setting::get('worktime', 'department') ),
        ]);
    }

    public function store( ) {
        $id = getgpc('id');
        $row = getgpc('row');

        $me = Request::$auth;

        $db = DB::write();

        $row['deadline'] = strtotime($row['deadline']);
        $row['changer'] = $me->id;

        if ($id) {
            $task = DB::find('tasks', $id);
            foreach ($row as $k => $v) {
                if ($task->$k == $v) {
                    unset($row[$k]);
                }
            }

            if (empty($row)) {
                return $this->onChanged( $id );
            }
        } else {
            $row['author'] = $me->id;
            $row['status'] = 12;
        }

        $now = date('Y-m-d H:i:s');
        $row['updated_at'] = $now;

        if (isset($row['content'])) {
            $row['content'] = removehost($row['content']);
        }

        $this->onChange( $row );

        if ($id) {
            if (!in_array($task->caty, $donotchange)) {
                $this->addlog($task, $row);
            }
            $db->update('tasks')->cols($row)->where('id='.$id)->query();
        } else {
            $row['created_at'] = $now;
            $id = $db->insert('tasks')->cols($row)->query();
        }

        return $this->onChanged( $id );
    }

    private function onChanged( $id ) {
        if (Request::isajax()) {
            return Request::response('');
        } else {
            return Request::redirect( '/task/show/' . $id );
        }
    }

    private function addlog( $old, $update ) {
        $monitor = array(
            'title', 'content', 'caty',
            'priority', 'department', 'status',
            'tag', 'pro', 'deadline',
            'changer', 'leader', 'tester'
        );
        $changed = array( );
        foreach ($monitor as $col) {
            if (isset($update[$col]) && $update[$col] != $old->$col) {
                $changed[$col] = $old->$col;
            }
        }

        if (empty($changed)) {
            return;
        }

        if (isset($changed['caty'])) {
            $row = DB::find('titles', $changed['caty']);
            $changed['caty'] = $row->name;
        }
        if (isset($changed['department'])) {
            $row = DB::find('titles', $changed['department']);
            $changed['department'] = $row->name;
        }

        if (isset($changed['priority'])) {
            $changed['priority'] = Setting::get('worktime', 'priority')[$changed['priority']];
        }
        if (isset($changed['status'])) {
            $changed['status'] = Setting::get('worktime', 'status')[$changed['status']];
        }

        if (isset($changed['tag'])) {
            $row = DB::find('tags', $changed['tag']);
            $changed['tag'] = $row->name;
        }

        if (isset($changed['pro'])) {
            $row = DB::find('pros', $changed['pro']);
            $changed['pro'] = $row->name;
        }

        foreach (['author', 'leader', 'tester', 'changer'] as $col) {
            if (isset($changed[$col])) {
                $row = DB::find('users', $changed[$col]);
                $changed[$col] = $row->name;
            }
        }

        $changed['pid'] = $old->id;
        $changed['changer'] = Request::$auth->name;
        $changed['created_at'] = $update['updated_at'];

        DB::write()->insert('tasklogs')->cols($changed)->query();
    }

    private function onChange( &$row ) {
        if (isset($row['tag'])) {
            $tag = DB::find('tags', $row['tag']);
            $row['pro'] = $tag->pro;
        }

        if (isset($row['leader'])) {
            $leader = DB::find('users', $row['leader']);
            $row['department'] = $leader->department;
        }
    }

    public function show($id) {
        $db = DB::write();
        $task = $db->row('select * from tasks where id=' . $id);

        $relatedsql = sprintf('select * from tasks where related = %s order by department, status, id limit 10', $id);
        View::addData([
            'task' => $task,
            'tasks' => $db->query($relatedsql),
            'feedbacks' => $db->query("select * from feedbacks where pid=$id"),
            'users' => $this->getSortedusers(),
            'prioritys' => Setting::get('worktime', 'priority'),
            'tags' => DB::keyBy('select id, name, pro from tags'),
            'pros' => DB::keyBy('select * from pros'),
            'catys' => DB::keyBy( "select * from titles where caty = " . Setting::get('worktime', 'caty') ),
            'departments' => DB::keyBy( "select * from titles where caty = " . Setting::get('worktime', 'department') )
        ]);

        return View::render('task-show', [
            'logs' => $db->query("select * from tasklogs where pid=$id")
        ]);
    }

    public function related( ) {
        $db = DB::write();

        $related = getgpc('related', 0);
        $id = getgpc('id');

        if ($related && $related != $id) {
            $db->update('tasks')->cols(['related' => $id])->where('id='.$related)->query();
        }

        $relatedsql = sprintf('select * from tasks where related = %s order by department, status, id limit 10', $id);
        View::addData([
            'tasks' => $db->query($relatedsql),
            'users' => $this->getSortedusers(),
            'prioritys' => Setting::get('worktime', 'priority'),
            'tags' => DB::keyBy('select id, name, pro from tags'),
            'pros' => DB::keyBy('select * from pros'),
            'catys' => DB::keyBy( "select * from titles where caty = " . Setting::get('worktime', 'caty') ),
            'departments' => DB::keyBy( "select * from titles where caty = " . Setting::get('worktime', 'department') )
        ]);

        return View::render('task-list-content');

    }

    public function content( $id ) {
        Request::response( DB::find('tasks', $id)->content );
    }

    public function upload( ) {
        $a = array( 'err' => 'do not recive file.' );

        if (!isset($_FILES['file'])) {
            return Request::json( $a );
        }

        $filedir = '/'.date('Ym');
        $homedir = Setting::get('app', 'images');

        $uploaddir = $homedir . DBNAME . $filedir;
        if (!is_dir($uploaddir)) {
            @mkdir($uploaddir);
        }

        $file = $_FILES['file'];

        if (0 == $file['size']) {
            return Request::json( $a );
        }

        $filename = time() . '_' . rand( 10000, 99999 );
        $uploadfile = $uploaddir . '/' . $filename;
        if (!file_exists($uploadfile)) {
            @move_uploaded_file($file['tmp_name'], $uploadfile);
        }

        $a['path'] = $filedir . '/' . $filename;

        return Request::json( $a );
    }

    public function diff( $table, $oldid, $newid, $islog = 0 ) {

        if ($islog) {
            $old = DB::find( $table . 'logs', $oldid );
            $new = DB::find( $table . 'logs', $newid );
        } else {
            $old = DB::find( $table . 'logs', $oldid );
            $new = DB::find( $table . 's', $newid );
        }


        if ('task' == $table) {
            $diff = new I\HtmlDiff( $old->content, $new->content );
        } else {
            $diff = new I\HtmlDiff( $old->message, $new->message );
        }
        $diff->build();
        // echo "<h2>Old html</h2>";
        // echo $diff->getOldHtml();
        // echo "<h2>New html</h2>";
        // echo $diff->getNewHtml();
        // echo "<h2>Compared html</h2>";
        // echo $diff->getDifference();

        return Request::response( $diff->getDifference() );
        return View::render('diff', ['diff' => $diff, 'old' => $old, 'new' => $new]);
    }
}
