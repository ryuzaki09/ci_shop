<?php

class Basket extends CI_Controller {
    
    function __construct(){
        parent::__construct();
        $this->load->model('commonmodel');
        $this->load->model('productsmodel');
		$this->load->library('loadpage');
		$this->load->library('auth');
				
    }
	
	
	function index(){
		
		$this->shoppingbasket();
		
	}
	
	function shoppingbasket(){
		
		if($this->input->post('update') == "Update Cart"){
			foreach($this->input->post() AS $postdata => $value):
				$data[] = $value;
			
			endforeach;

			$this->cart->update($data);
		}
		
		
		$this->loadpage->loadpage('basket/list', @$data);
	}
	
	function checkout(){
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
		foreach($this->input->post() AS $order):

		endforeach;
		echo "<pre>";
		print_R($_POST);
		echo "</pre>";


	}


	public function testpaypal(){
		$this->load->library("paypal");
		$paypal_token = $this->paypal->getAccessToken();
		if($paypal_token)
			print_R($paypal_token);
	}
	
	
	
}
?>
