<?php

class Curl {
	public $ch;

	public function __construct(){
		$this->CI =& get_instance();
		$this->CI->ch = curl_init();
	}

	public function curl_url($url){
		if(!empty($url) && is_string($url)){
			curl_setopt($this->CI->ch, CURLOPT_URL, $url);
			return $this->CI;
		}
	}
	
	public function headers($headers){
		if(is_bool($headers) && ($headers))
			curl_setopt($this->CI->ch, CURLOPT_HEADER, true);
		else
			curl_setopt($this->CI->ch, CURLOPT_HEADER, false);
	}
	
	public function curl_ssl($ssl){
		if(is_bool($ssl) && ($ssl))
			curl_setopt($this->CI->ch, CURLOPT_SSL_VERIFYPEER, true);
		else
			curl_setopt($this->CI->ch, CURLOPT_SSL_VERIFYPEER, false);
		
	}

	public function curl_post($curl_post){
		if(is_bool($curl_post))
			curl_setopt($this->CI->ch, CURLOPT_POST, $curl_post);
	}

	public function returnTransfer($returnTransfer){
		if(is_bool($returnTransfer))
			curl_setopt($this->CI->ch, CURLOPT_RETURNTRANSFER, $returnTransfer);
	}

	public function userPwd($userPwd){
		if(is_string($userPwd))
			curl_setopt($this->CI->ch, CURLOPT_USERPWD, $userPwd);
	}

	public function postfields($postfields){
		if(is_string($postfields))
			curl_setopt($this->CI->ch, CURLOPT_POSTFIELDS, $postfields); 
	}

	public function http_header($http_headers){
		if(is_array($http_headers) && !empty($http_headers))
			curl_setopt($this->CI->ch, CURLOPT_HTTPHEADER, $http_headers);
	}

	public function curlexec(){
		$result = curl_exec($this->CI->ch);
		curl_close($this->CI->ch);
		return $result;
	}

}
