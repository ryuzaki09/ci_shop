<?php

class Orders extends CI_Controller {

    public function __construct(){
        parent::__construct();
		$this->load->model('ordersmodel');
        $this->load->library('adminpage');
        $this->auth->is_logged_in();        
    }

	
    public function pending(){
        $data['result'] = $this->ordersmodel->get_orders('pending');

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
		
        $data['order_info']	= $result;
        $data['paypal_result'] = $paypal_result;
        $data['pagetitle'] 	= "Order Details";

        $this->adminpage->loadpage('admin/orders/details', $data);

    }

    public function approved(){
	
        $data['result'] = $this->ordersmodel->get_orders('approved');
		$data['pagetitle'] = "Approved Orders";

		$this->adminpage->loadpage("admin/orders/approved", $data);
    }

    public function disapproved(){
		$data['result'] = $this->ordersmodel->get_orders('disapproved');
		$data['pagetitle'] = "Disapproved Orders";

		$this->adminpage->loadpage("admin/orders/disapproved", $data);
    }

    public function approve_order($oid){
	
		if($this->input->post('approve_order', true) == "Approve"){
			$this->logger->info("Admin approving order: ".$oid);

			if(!is_numeric($oid)){
				$this->session->set_flashdata("error_msg", "Order Number invalid");
				redirect("/admin/orders/pending");
			}

			$result = $this->ordersmodel->admin_approve_disapprove_order($oid, 'approved');
			if($result)
				$this->session->set_flashdata("message", "<div class='alert alert-success'>Order $oid has been approved</div>");
			else
				$this->session->set_flashdata("message", "<div class='alert alert-danger'>Order $oid cannot be approved</div>");

			redirect("/admin/orders/pending");
			
		}
    }

    public function disapprove_order(){
		if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH']=='XMLHttpRequest')) {
			$oid = $this->input->post('oid', true);
			$this->logger->info("Disapprove order: ".$oid);

			$result = $this->ordersmodel->admin_approve_disapprove_order($oid, 'disapproved');
			echo ($result)
				? "true"
				: "false";
			
		}

    }

    public function payments_lookup(){

		if($this->input->post("search") == "List Payments!"){
			//loop through POST to concatenate the filter string
			$filter = null;
			foreach($this->input->post() AS $formdata => $value):
				if($value && $formdata != "search"){
					if($formdata == "start_time" || $formdata == "end_time"){
						$date = new DateTime($value);
						$filter .= $formdata."=".$date->format("Y-m-d")."T00:00:00Z&";
					} else {
						$filter .= $formdata."=".$value."&";
					}
				}
			endforeach;

			$this->logger->info("Paypal filter string: ".$filter);
			$this->load->library('paypal');
			$data['result'] = $this->paypal->list_payments($filter);

		}

		$data['pagetitle'] = "List paypal payments";
		$this->adminpage->loadpage("admin/orders/list_payments", $data);


    }

    
    public function paypalrefund($oid, $sale_id, $total, $currency){
        
		$this->logger->info("refund sale id: ".$sale_id." ".$total." ".$currency);

		$this->load->library('paypal');

		try {
			$response =	$this->paypal->refund($sale_id, $total, $currency);

			$uid = $this->session->userdata("uid");

			if($response && $response->state == "completed"){
				$transaction_data = array("oid" => $oid,
											"customer_id" => $uid,
											"subtotal" => $total,
											"total" => $total,
											"external_ref" => "paypal refund"
											);
				$this->ordermodel->refundsale($transaction_data);

				redirect("/admin/orders/approved");
			}

		} catch(Exception $e) {
			$this->logger->error("cannot refund payment: ".$e->getMessage());
		}


    }

    public function ajaxApproveOrder(){
	
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH']=='XMLHttpRequest')){
			$oid = $this->input->post("oid", true);    
			$result = $this->ordersmodel->admin_approve_disapprove_order($oid, 'approved');

			echo ($result)
			? "true"
			: "false";
		}
    }
}
