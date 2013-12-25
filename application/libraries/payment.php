<?php

class Payment {
	private $payment_session = array();

	public function __construct(){
		$this->CI =& get_instance();
		$this->CI->payment_session = $this->CI->session->userdata("payment");
	}

	public function setValue($item, $value){
		// if(!$this->CI->session->userdata("payment")){
		if(!$this->CI->payment_session){
			$this->CI->session->set_userdata("payment", array($item => $value));
			// $this->CI->session->set_userdata(
		} else {
			
		// if(!$this->session->userdata('payment')){
			$this->CI->session->set_userdata($this->CI->payment_session = array($item), $value);
		// } else {
		// 	$this->session
		}

	}

	public function getAllValues(){

		if($this->CI->session->userdata('payment')){
			return $this->CI->session->userdata('payment');
		}
	}

	public function destroyValues(){
		$this->CI->session->unset_userdata("payment");
		$this->CI->session->unset_userdata("paypal_token");
	}

}
