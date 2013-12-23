<?php

class Curl {
	private $url;
	private $header;
	private $postdata;
	private $curl;

	public function __construct(){
		$this->CI =& get_instance();
		$this->CI->curl = curl_init();
	}

	
	public function headers($headers){
		if(is_bool($headers) && ($headers))
			curl_setopt($this->CI->curl, CURLOPT_HEADER, true);
		else
			curl_setopt($this->CI->curl, CURLOPT_HEADER, false);
	}
	
	public function curl_ssl(){
		
	}

}
