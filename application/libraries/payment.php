<?php

class Payment {
	private $payment_session;
	private $payment_data = array( "payment" => array(
												"paypal_token" => false,
												"pay_method"	=> false
												)
								);

	public function __construct(){
		$this->CI =& get_instance();
		$this->CI->payment_session = $this->CI->session->userdata("payment");
	}

	public function setValue($item, $value){
		$this->CI->payment_data["payment"][$item] = $value;

		if(!$this->CI->payment_session){
			$this->CI->session->set_userdata($this->CI->payment_data);
		} else {
			
			$this->CI->session->set_userdata($this->CI->payment_data);
		}

	}

    public function getValue($item){
        $paymentdata = $this->CI->session->userdata("payment");
        return $paymentdata[$item];
    }

    public function deleteValue($item){
       unset($this->CI->payment_data["payment"][$item]); 
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
