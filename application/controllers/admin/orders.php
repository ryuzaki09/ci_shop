<?php

class Orders extends CI_Controller {

	public function __construct(){
		parent::__construct();
        $this->load->model('commonmodel');
		$this->load->model('ordersmodel');
        $this->load->library('adminpage');
        $this->auth->is_logged_in();        
	}

	
	public function pending(){
		$data['result'] = $this->ordersmodel->get_pending_orders();

		$data['pagetitle'] = "Orders | Pending";

		$this->adminpage->loadpage("admin/orders/pending", $data);
	}

	public function details($order_no){
		//get paypal references from the order no.
		$result = $this->ordersmodel->get_order_details($order_no);	
		$paypal_result = array();

		if($result){
			$this->logger->info("order details retreived for order: ".$order_no);
			$this->load->library('paypal');
			$this->logger->info("Retrieving access token");
			$access_details = $this->paypal->getAccessToken();

			if($access_details){
				$this->logger->info("access details retreived");
				$external_ref	= json_decode($result[0]['external_ref']);

				if($external_ref){
					$this->logger->info("external ref retreived");
					$paypal_result = $this->paypal->get_payment_resource($access_details->access_token, $external_ref->paypal_id);
				} else {
					$this->logger->info("There is no external ref info");
				}

			}
		}
		
		// echo "<pre>";
		// print_r($paypal_result);
		// echo "</pre>";
		$data['order_info']	= $result;
		$data['paypal_result'] = $paypal_result;
		$data['pagetitle'] 	= "Order Details";

		$this->adminpage->loadpage('admin/orders/details', $data);

	}

}
