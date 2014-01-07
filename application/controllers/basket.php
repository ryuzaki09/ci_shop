<?php

class Basket extends CI_Controller {
    
    public function __construct(){
        parent::__construct();
        $this->load->model('commonmodel');
        $this->load->model('productsmodel');
		$this->load->library('loadpage');
		$this->load->library('auth');
				
    }
	
	
	function index(){
		
		$this->shoppingbasket();
		
	}
	
	//Update the shopping basket at the basket page
	public function shoppingbasket(){
		
		if($this->input->post('update') == "Update Cart"){
			foreach($this->input->post() AS $postdata => $value):
				$data[] = $value;
			
			endforeach;

			$this->cart->update($data);
		}
		
		$this->loadpage->loadpage('basket/list', @$data);
	}
	
	//confirmation page to checkout and require customer to be logged in.
	public function checkout(){
		$data['environment'] = (ENVIRONMENT == "development")
								? true
								:false;

		if($this->auth->is_logged_in()){
			$this->load->model('usermodel');
			$uid = $this->session->userdata('uid');
			$data['userdata'] = $this->usermodel->db_get_userdetails($uid);
			
			$data['pagetitle'] = "Confirmation Page";
			$this->loadpage->loadpage('basket/confirm', $data);
		} else {
			redirect(base_url().'user/login');
		}
		
	}
	
	public function process_checkout(){
		$price = $this->input->post('price', true);
		$qty = $this->input->post('qty', true);
		$order_data = array();
		//grab all product info from post and pass to process payment
		$i = 0;
		foreach($this->input->post() AS $order):
			$order_data[$i]['quantity']
		endforeach;
		echo "<pre>";
		print_R($this->input->post());
		echo "</pre>";

	}


	public function process_paypal($order_data=false){
		$this->load->library("paypal");
		$this->load->library("payment");
		$paypal_token = $this->paypal->getAccessToken();
		if($paypal_token){
			$this->payment->setValue("paypal_token", $paypal_token->access_token);
			$this->payment->setValue("pay_method", "paypal");
			echo "<pre>";
			$payment_session = $this->session->all_userdata();

			print_R($payment_session);
			echo "</pre>";
			$payment_result = $this->paypal->createPayment($paypal_token->access_token);
			$this->payment->destroyValues();
			if($payment_result && $payment_result->links[1]->rel == "approval_url"){
				//redirect customer to get approval of sale
				redirect($payment_result->links[1]->href);
				// echo "<pre>";
				// print_r($payment_result);
				// echo "</pre>";
			}
		}
	}

	
	//once customer approves, execute payment and save to DB
	public function paypal_callback(){
		$this->load->library("paypal");
		$payer_id 	= $this->input->get("PAYERID", true);
		$token		= $this->input->get("token", true);
		if($payer_id && $token){
			$result = $this->paypal->execute_payment($payer_id, $token);
		
		}

	}

		
	// private function createPaypalPayment($access_token){
	// 	$this->load->library("paypal");
	// 	$payment = $this->paypal->createPayment($access_token);
	// }
	
	
}
?>
