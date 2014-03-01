<?php

class Paypal extends CI_Controller {

    public function __construct(){
	parent::__construct();
	$this->load->model('ordersmodel');
        $this->load->library('adminpage');
        $this->auth->is_logged_in();        

    }


    public function payments_lookup(){

	if($this->input->post("search") == "List Payments!"){
	    $this->load->library('paypal');
	    echo "test";
	    $data['result'] = $this->paypal->list_payments();
	    print_r($data);

	}

	$data['pagetitle'] = "List paypal payments";
	$this->adminpage->loadpage("admin/paypal/list_payments", $data);


    }





}
