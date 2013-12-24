<?php

class Paypal {
	private $url = false;
	private $secret = false;
	private $clientId = false;

	public function __construct(){
		$this->CI =& get_instance();
		$this->CI->load->library('curl');
		$this->CI->url		= commonclass::getConfig("shoplongdestiny.paypal_endpoint");
		$this->CI->clientId = commonclass::getConfig("shoplongdestiny.paypal_client_id");
		$this->CI->secret 	= commonclass::getConfig("shoplongdestiny.paypal_secret");
	}

	public function getAccessToken(){
		$curl_url = $this->CI->url ."/v1/oauth2/token";

		$this->CI->curl->curl_url($curl_url);
		$this->CI->curl->headers(false);
		$this->CI->curl->curl_ssl(false);
		$this->CI->curl->curl_post(true);
		$this->CI->curl->returnTransfer(true);
		$this->CI->curl->userPwd($this->CI->clientId.":".$this->CI->secret);
		$this->CI->curl->postfields("grant_type=client_credentials");

		$response = $this->CI->curl->curlexec();

		if(empty($response))
			$this->CI->logger->info("cannot get paypal access token");
		else {
			$this->CI->logger->info("paypal access token received");
			return (json_decode($response));	
		}
	}


	public function createPayment($access_token){
		$this->CI->logger->info("process create payment");
		$payment_url = $this->CI->url ."/v1/payments/payment";
		
		$saledata = array("intent" => "sale",
							"redirect_urls" => array("return_url" => "http://www.google.com", 
													"cancel_url" => "http://www.ebuyer.com"),
							"payer" => array("payment_method" => "paypal"),
							"transactions" => array(
												array(
													"amount" => array(
																		"total" => "7.47", 
																		"currency" => "USD"
																	),
													"description" => "This is a test payment transaction description"
													)
												)
							);
		$sale_json = json_encode($saledata);

		$headers_data = array("Content-Type: application/json",
								"Authorization: Bearer ".$access_token,
								"Content-length: ".strlen($sale_json));

		$this->CI->curl->curl_url($payment_url);
		$this->CI->curl->headers(false);
		$this->CI->curl->curl_ssl(false);
		$this->CI->curl->curl_post(true);
		$this->CI->curl->returnTransfer(true);
		$this->CI->curl->http_header($headers_data);
		$this->CI->curl->postfields($sale_json);
		
		$response = $this->CI->curl->curlexec();
		$this->CI->curl->closeCurl();
		
		if(($response) && !empty($response))
			return json_decode($response);
	}





}
