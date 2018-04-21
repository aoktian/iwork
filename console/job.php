<?php
class Controller {

    public function handle( ) {
        $this->worker( );
    }

    public function worker( ) {
        $redis = DB::redis();
        while ( true ) {
            $a = $redis->blpop("jobs", 0);

            $cmd = sprintf('php %s/artisan %s', ROOT_DIR, escapeshellarg($a[1]) );
            echo $cmd . "\n";
            $output = shell_exec( $cmd );
            echo $output . "\n";

            sleep( 10 );
        }
    }

}
