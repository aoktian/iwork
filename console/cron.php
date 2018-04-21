<?php
class Controller {

    private $items = NULL;

    public function handle( ) {
        $times = explode('-', date('Y-m-d-H-i-w'));
        $year = (int)$times[0];
        $month = (int)$times[1];
        $day = (int)$times[2];
        $hour = (int)$times[3];
        $min = (int)$times[4];
        $week = (int)$times[5] + 1;

        $this->items = Setting::get('cron');

        $this->everyMinute();

        if (!($min % 5)) {
            $this->everyFiveMinutes();
        }
        if (!($min % 10)) {
            $this->everyTenMinutes();
        }
        if (!($min % 30)) {
            $this->everyThirtyMinutes();
        }

        if (0 == $min) {
            $this->hourly();
            $this->hourlyAt( $hour );
        }

        if (0 == $hour) {
            $this->daily();
        }

        $this->dailyAt( $hour, $min );
        $this->weekly( $week, $hour, $min );
        $this->monthly( $day, $hour, $min );
        $this->monthlyOn( $month, $day, $hour, $min );
    }

    private function everyMinute( ) {
        if (!isset($this->items['everyMinute'])) {
            return;
        }

        foreach ($this->items['everyMinute'] as $cron) {
            exec( $cron['cmd'] );
        }
        [
        'name' => 'ls',
        'cmd' => "ls -al",
        'out' => '/tmp/php_crontab.log',
        'time' => '* * * * *',
        ];
    }

    private function everyFiveMinutes( ) {

    }

    private function everyTenMinutes( ) {
    }
    private function everyThirtyMinutes( ) {
    }

    private function hourly( ) {
    }

    private function hourlyAt( $hour ) {

    }

    private function daily( ) {

    }

    private function dailyAt( $hour, $min ) {

    }

    private function weekly( $week, $hour, $min ) {

    }

    private function monthly( $day, $hour, $min ) {
    }
    private function monthlyOn( $month, $day, $hour, $min ) {

    }

}
