<?php

class Logger {

	public function __construct(){
		$this->CI =& get_instance();
	}


	public function info($info){
		if($info){
			$file = "/var/log/dev/shoplongdestiny/shoplongdestiny.log";
			$date = date('Y/m/d H:i:s');
			$fp = fopen($file, "a");
			fwrite($fp, "[".$date."][INFO] - ".$info."\n");
			fclose($fp);
		}
	}

    public function error($error){
        if($error){
			$file = "/var/log/dev/shoplongdestiny/shoplongdestiny.log";
			$date = date('Y/m/d H:i:s');
			$fp = fopen($file, "a");
			fwrite($fp, "[".$date."][ERROR] - ".$error."\n");
			fclose($fp);
		}
    }
}
