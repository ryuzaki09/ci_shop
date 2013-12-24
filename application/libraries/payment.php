<?php

class Payment {

	public function __construct(){
		$this->CI =& get_instance();
	}

	public function setValue($item, $value){
		// if(!$this->session->userdata('payment')){
			$this->CI->session->set_userdata('payment', array($item => $value));
		// } else {
		// 	$this->session
		// }

	}

	public function getAllValues(){

		if($this->CI->session->userdata('payment')){
			return $this->CI->session->userdata('payment');
		}
	}

	public function destroyValues(){
		$this->CI->session->unset_userdata("payment");
	}

}
