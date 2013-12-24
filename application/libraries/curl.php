<?php

class Curl {
	private $ch;

	public function __construct(){
		$this->CI =& get_instance();
		// if(!$this->CI->ch){
			$this->CI->ch = curl_init();
		//}
	}

	public function curl_url($url){
		if(!empty($url) && is_string($url)){
			// echo $url."<br />";
			// print_r($this->CI->ch);
			curl_setopt($this->CI->ch, CURLOPT_URL, $url);
		}
	}
	
	public function headers($headers){
		if(is_bool($headers) && ($headers))
			curl_setopt($this->CI->ch, CURLOPT_HEADER, $headers);
	}
	
	public function curl_ssl($ssl){
		if(is_bool($ssl) && ($ssl))
			curl_setopt($this->CI->ch, CURLOPT_SSL_VERIFYPEER, $ssl);
		
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
		if($postfields)
			curl_setopt($this->CI->ch, CURLOPT_POSTFIELDS, $postfields); 
	}

	public function http_header($http_headers){
		if(is_array($http_headers) && !empty($http_headers))
			curl_setopt($this->CI->ch, CURLOPT_HTTPHEADER, $http_headers);
	}

	public function curlexec(){
		$result = curl_exec($this->CI->ch);
		return $result;
	}

	public function __destruct(){
		$this->CI->ch = false;
	}

	public function closeCurl(){
		curl_close($this->CI->ch);
	}

}
