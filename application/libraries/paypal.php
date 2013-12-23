<?php

class Paypal {

	public function __construct(){
		$this->CI =& get_instance();
	}

	public function getAccessToken(){
		$url = commonclass::getConfig("shoplongdestiny.paypal_endpoint");
		$url .= "/v1/oauth2/token";
		$clientId = commonclass::getConfig("shoplongdestiny.paypal_client_id");
		$secret = commonclass::getConfig("shoplongdestiny.paypal_secret");

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, false);	
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERPWD, $clientId.":".$secret);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
		$response = curl_exec($ch);
		
		curl_close($ch);

		if(empty($response))
			$this->CI->logger->info("cannot get paypal access token");
		else {
			$this->CI->logger->info("paypal access token received");
			return (json_decode($response));	
		}
	}





}
