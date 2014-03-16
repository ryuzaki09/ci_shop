<?php

class Vouchers extends CI_Controller {

    CONST CODE_CHARS	= 8;

    public function __construct(){
	parent::__construct();
	$this->load->library("adminpage");
    }

    
    public function create(){
	
	if($this->input->post('generate') == "Generate Voucher"){
	    $expiry_date = $this->input->post("expiry_date", true);
	    $letters = "abcdefghijklmnopqrstuvwxyz0123456789";
	    $voucher_code = substr(str_shuffle($letters), 0, self::CODE_CHARS);
	    // echo strtoupper($voucher_code);
	    // print_R($this->input->post());

	}

	$data['pagetitle'] = "Vouchers | Create";

	$this->adminpage->loadpage("admin/vouchers/create_edit", $data);
    }

    public function listing(){

	$data['pagetitle'] = "Voucher List";
	$this->adminpage->loadpage("admin/vouchers/list", $data);
    }

}
