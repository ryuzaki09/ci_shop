<?php

class Orders extends CI_Controller {

	public function __construct(){
		parent::__construct();
        $this->load->model('commonmodel');
        $this->load->library('adminpage');
        $this->auth->is_logged_in();        
	}

	
	public function pending(){

		$data['pagetitle'] = "Orders | Pending";

		$this->adminpage->loadpage("admin/orders/pending", $data);
	}

}
