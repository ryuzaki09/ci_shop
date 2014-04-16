<?php

class Vouchers extends CI_Controller {

    CONST CODE_CHARS	= 8;

    public function __construct(){
		parent::__construct();
		$this->load->library("adminpage");
		$this->load->model('ordersmodel');
    }

    
    public function create(){
	
		if($this->input->post('generate') == "Generate Voucher"){
	 
			$letters = "abcdefghijklmnopqrstuvwxyz0123456789";
			$voucher_code = substr(str_shuffle($letters), 0, self::CODE_CHARS);
			$insertdata = array('voucher_code' => strtoupper($voucher_code),
								'voucher_value'	=> $this->input->post('voucher_amount', true),
								'min_purchase'	=> $this->input->post('min_amount', true),
								'expiry_date' 	=> $this->input->post("expiry_date", true)." ".date('H:i:s'),
								'date_created'	=> date('Y-m-d H:i:s')
							);

			$this->logger->info("creating voucher: ".var_export($insertdata, true));

			$result = $this->ordersmodel->insertVoucher($insertdata);

			if($result){
				$this->session->set_flashdata('message', '<div class="alert alert-success">Voucher code created!</div>');
				redirect('/admin/vouchers/listing');
			} else {
				$data['message'] = "<div class='alert alert-danger'>Something went wrong! Cannot create voucher!</div>";
			}

		}

		$data['pagetitle'] = "Vouchers | Create";

		$this->adminpage->loadpage("admin/vouchers/create_edit", $data);
    }

    public function listing(){
	
		$data['result'] = $this->ordersmodel->getVoucherList();
		
		$data['pagetitle'] = "Voucher List";
		$this->adminpage->loadpage("admin/vouchers/list", $data);
    }

}
