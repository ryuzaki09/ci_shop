<?php

class Paypal {

	public function __construct(){
		$this->CI =& get_instance();
	}

	public function getAccessToken(){
		$this->CI->load->library('curl');
		$url = commonclass::getConfig("shoplongdestiny.paypal_endpoint");
		$url .= "/v1/oauth2/token";
		$clientId = commonclass::getConfig("shoplongdestiny.paypal_client_id");
		$secret = commonclass::getConfig("shoplongdestiny.paypal_secret");

		$this->CI->curl->curl_url($url);
		$this->CI->curl->headers(false);
		$this->CI->curl->curl_ssl(false);
		$this->CI->curl->curl_post(true);
		$this->CI->curl->returnTransfer(true);
		$this->CI->curl->userPwd($clientId.":".$secret);
		$this->CI->curl->postfields("grant_type=client_credentials");

		$response = $this->CI->curl->curlexec();

		if(empty($response))
			$this->CI->logger->info("cannot get paypal access token");
		else {
			$this->CI->logger->info("paypal access token received");
			return (json_decode($response));	
		}
	}





}
