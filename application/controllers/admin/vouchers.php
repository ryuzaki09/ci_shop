<?php

class Vouchers extends CI_Controller {

    public function __construct(){
	parent::__construct();
	$this->load->library("adminpage");
    }

    
    public function create(){
	
	if($this->input->post('generate') == "Generate Vouchers"){


	}

	$data['pagetitle'] = "Vouchers | Create";

	$this->adminpage->loadpage("admin/vouchers/create_edit", $data);
    }

    public function listing(){

	$data['pagetitle'] = "Voucher List";
	$this->adminpage->loadpage("admin/vouchers/list", $data);
    }

}
