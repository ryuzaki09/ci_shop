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


	public function createPayment($access_token, $order_data=false){
		$this->CI->logger->info("process create payment");
		$payment_url = $this->CI->url ."/v1/payments/payment";
		
		//put all sale data together
		$saledata = array("intent" => "sale",
							"redirect_urls" => array("return_url" => "http://shoplongdestiny.dev/basket/paypal_callback", 
													"cancel_url" => "http://www.ebuyer.com"),
							"payer" => array("payment_method" => "paypal"),
							"transactions" => array(
												array(
													"amount" => array(
																		"total" => "7.47", 
																		"currency" => "USD",
																		"details" => array(
																						"subtotal" => "5.00",
																						"tax"	=> "1.00",
																						"shipping"	=> "1.47"
																						)
																	),
													"description" => "This is a test payment transaction description",
													"item_list" => array(
																	"items" => array(
																				array(
																					"quantity" 	=> "1",
																					"name" 	=> "Hat",
																					"price"	=> "5.00",
																					"currency"	=> "USD"
																					 )	
																					)
																	)
													)
												)
							);
		$sale_json = json_encode($saledata);
		
		//send request to paypal
		$headers_data = array("Content-Type: application/json",
								"Authorization: Bearer ".$access_token,
								"Content-length: ".strlen($sale_json));
		try {
			$this->CI->curl->curl_url($payment_url);
			$this->CI->curl->headers(false);
			$this->CI->curl->curl_ssl(false);
			$this->CI->curl->curl_post(true);
			$this->CI->curl->returnTransfer(true);
			$this->CI->curl->http_header($headers_data);
			$this->CI->curl->postfields($sale_json);
			
			$response = $this->CI->curl->curlexec();
			$this->CI->curl->closeCurl();
		
		} catch(Exception $e){
			$this->CI->logger->info($e->getMessage());	
		}

		if(($response) && !empty($response)){
			$this->CI->logger->info("Payment created");
			return json_decode($response);

		} else {
			$this->CI->logger->info("Unable to create paypal payment");
			return;
		}

	}

	
	public function execute_payment($payer_id, $token){
		$execute_url .= $this->CI->url."/v1/payments/payment/";
		try {
			$this->CI->curl->curl_url($execute_url);
			$this->CI->curl->headers(false);
			$this->CI->curl->ssl(false);
			$this->CI->curl->returnTransfer(true);


		} catch(Exception $e) {
			echo $e->getMessage();
		}

	}



}
