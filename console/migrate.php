<?php
use I\DB;
class Controller {

    public function handle( ) {
        $db = DB::write();

        $sql = <<<EOT
CREATE TABLE IF NOT EXISTS migrations (
batch int(11),
migration text,
PRIMARY KEY (`batch`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
EOT;
        $db->query($sql);

        $row = $db->row("select max(batch) as num FROM `migrations`");
        $batch = $row->num ? $row->num : 0;
        $batch++;

        $dir = 'database/migrations';
        $handle = opendir($dir);

        $db->beginTrans();
        while (false !== ($file = readdir($handle))) {
            if ($file != '.' && $file != '..') {
                $ibatch = intval($file);
                if ($ibatch < $batch) {continue;}

                $content = file_get_contents($dir . '/' . $file);

                $sqls = explode(";\n", $content);
                foreach ($sqls as $sql) {
                    $sql = trim($sql);
                    if ($sql) {
                        $db->query( $sql );
                        // echo $sql;
                    }
                }

                $db->insert('migrations')->cols(array(
                    'batch'=> $ibatch,
                    'migration'=> $content
                ))->query();
            }
        }

        $db->commitTrans();
    }

    public function seeder( ) {
        $dir = 'database/seeds';

        $file = getgpc('file');
        if ($file) {
            $this->_seeder( $dir . '/' . $file );
            return;
        }

        $handle = opendir($dir);
        while (false !== ($file = readdir($handle))) {
            if ($file != '.' && $file != '..') {
                $this->_seeder( $dir . '/' . $file );
            }
        }
    }

    private function _seeder( $file ) {
        $db = DB::write();

        $content = file_get_contents($file);

        $sqls = explode(";\n", $content);
        foreach ($sqls as $sql) {
            $sql = trim($sql);
            if ($sql) {
                $db->query( $sql );
                echo $sql;
            }
        }
    }
}
