<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Paypal {
    private $url;
    private $secret;
    private $clientId;
    private $return_url; 
    private $cancel_url;

    public function __construct(){
	parent::__construct();
	$this->CI =& get_instance();
	$this->CI->load->library('curl');
	$this->CI->url		= commonclass::getConfig("shoplongdestiny.paypal_endpoint");
	$this->CI->clientId 	= commonclass::getConfig("shoplongdestiny.paypal_client_id");
	$this->CI->secret 	= commonclass::getConfig("shoplongdestiny.paypal_secret");
	$this->CI->return_url	= base_url()."basket/paypal_callback";
	$this->CI->cancel_url	= base_url()."basket/checkout";
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
	    $this->CI->logger->error("cannot get paypal access token");
	else {
	    $this->CI->logger->info("paypal access token received");
	    return (json_decode($response));	
	}
    }


    public function createPayment($access_token, $order_data=false, $additional_prices){
	$this->CI->logger->info("process create payment");
	$payment_url = $this->CI->url ."/v1/payments/payment";
		
	//put all sale data together
	$saledata = array("intent" => "sale",
			"redirect_urls" => array("return_url" => $this->CI->return_url,
						"cancel_url" => $this->CI->cancel_url,
						),
						"payer" => array("payment_method" => "paypal"),
						"transactions" => array(
									array(
										"amount" => array(
												"total" => $additional_prices['total'], 
												"currency" => $additional_prices['currency'],
												"details" => array(
														"subtotal" => $additional_prices['subtotal'],
														"tax"	=> $additional_prices['tax'],
														"shipping"	=> $additional_prices['shipping']
														)
												),
										"description" => "This is a test payment transaction description",
										"item_list" => array("items" =>$order_data )
									    )
						)
			);
	$this->CI->logger->info("sale data: ".var_export($saledata, true));
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
	    // $this->CI->curl->closeCurl();
		
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

	
    public function execute_payment($id, $payer_id, $access_token){
        $this->CI->logger->info("Starting to execute payment");
        $this->CI->logger->info("id: ".$id." payerid: ".$payer_id." token: ".$access_token);

	$execute_url = $this->CI->url."/v1/payments/payment/".$id."/execute";
	$postdata = array('payer_id' => $payer_id);
        $post_json = json_encode($postdata);
        $headers_data = array("Content-Type: application/json",
				"Authorization: Bearer ".$access_token,
				"Content-length: ".strlen($post_json));
	try {
	    $this->CI->curl->curl_url($execute_url);
	    $this->CI->curl->headers(false);
	    $this->CI->curl->curl_ssl(false);
	    $this->CI->curl->curl_post(true);
	    $this->CI->curl->returnTransfer(true);
            $this->CI->curl->http_header($headers_data);
	    $this->CI->curl->postfields($post_json);

            $response = $this->CI->curl->curlexec();
	    $this->CI->curl->closeCurl();
            $json_response = json_decode($response);
            $this->CI->logger->info("response: ".var_export($json_response, true));	

	} catch(Exception $e) {
	    $this->CI->logger->error("Something went wrong with paypal execution: ".$e->getMessage());
	}

        if(($response) && !empty($response)){
	    $this->CI->logger->info("Payment executed successfully");
	    return json_decode($response);

	} else {
	    return;
	}

    }

    public function get_payment_resource($access_token, $paypal_id){
	$this->CI->logger->info("Getting payment resource from paypal");
	$this->CI->logger->info("token: ".$access_token." paypal id: ".$paypal_id);
		
        $headers_data = array("Content-Type: application/json",
				"Authorization: Bearer ".$access_token);
				// "Content-length: ".strlen($post_json));

	$url = $this->CI->url."/v1/payments/payment/".$paypal_id;

	try {
	    $this->CI->curl->curl_url($url);
	    $this->CI->curl->headers(false);
	    $this->CI->curl->curl_ssl(false);
	    $this->CI->curl->curl_get(true);
	    $this->CI->curl->returnTransfer(true);
	    $this->CI->curl->http_header($headers_data);

	    $response = $this->CI->curl->curlexec();
	    $this->CI->curl->closeCurl();
	    $json_response = json_decode($response);
	    $this->CI->logger->info("response: ".var_export($json_response, true));	
		
	} catch (Exception $e) {
	    $this->CI->logger->error("Cannot get payment resource: ".$e->getMessage());
	}

	if(($response) && !empty($response)){
	    $this->CI->logger->info("Payment resource retreived");
	    return $json_response;

	} else {
	    return;
	}

    }

    
    public function list_payments(){
	$access_token = $this->getAccessToken();
	return $access_token;

    }
}
