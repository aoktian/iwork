<?php
namespace I;
use Workerman\MySQL\Connection;

class DB {
    private static $writedb = NULL;
    public static function write( ) {
        if (self::$writedb) {
            return self::$writedb;
        }

        $config = (object)Setting::get('database', 'mysql');
        self::$writedb = new Connection($config->host,
            3306,
            $config->username,
            $config->password,
            $config->name
        );
        return self::$writedb;
    }

    public static function find( $table, $id ) {
        return DB::write()->select('*')->from($table)->where('id= :id')->bindValues(array('id'=>$id))->row();
    }

    public static function all( $table ) {
        return DB::write()->query("select * from $table");
    }

    public static function keyBy( $sql ) {
        $rtn = array();
        $rows = DB::write()->query($sql);
        foreach ($rows as $row) {
            $rtn[$row->id] = $row;
        }

        return $rtn;
    }

}
