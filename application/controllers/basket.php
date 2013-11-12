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
			/*
			echo "<pre>";
			print_r($data);
			echo "</pre>";*/
			$this->cart->update($data);
		}
		
		
		$this->loadpage->loadpage('basket/list', $data);
	}
	
	function checkout(){
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
	
	
	
	
	
}
?>