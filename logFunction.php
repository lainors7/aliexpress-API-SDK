<?php
class Log{
    public function __construct($log_name, $page_name){
        if (!file_exists('logs/'.date('d_m_Y'))) {
            $log_name = date('d_m_Y') . '.log';
        }
        $this->log_name = $log_name;

        $this->app_id = uniqid(); //give each process a unique ID for differentiation
        $this->page_name = $page_name;

        $this->log_file = 'logs/'.$this->log_name;
        $this->log = fopen($this->log_file, 'a');
    }
    public function log_msg($msg){ //write the msg
        $log_line = join(' : ', array(date('d/m/Y h:i:s'), $this->page_name, $this->app_id, $msg));
        fwrite($this->log, $log_line . "\n");
    }
    function __destruct(){ //makes sure to close the file and write lines when the process ends.
        //$this->log_msg("Closing log");
        fclose($this->log);
    }
}