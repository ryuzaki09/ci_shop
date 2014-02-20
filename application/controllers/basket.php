<?php

class Basket extends CI_Controller {
    
    public function __construct(){
        parent::__construct();
        $this->load->model('commonmodel');
        $this->load->model('productsmodel');
        $this->load->library('loadpage');
        $this->load->library('auth');
				
    }

    public function index(){
		
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
        $data['pagetitle']  = "Shopping Basket";	
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
        $order_data = array();
        $customer_id = $this->session->userdata('uid');
        
        //grab all product info from post and pass to process payment
        $i = 0;
        $subtotal = 0;
        foreach($this->input->post() AS $order):
            $order_data[$i]['quantity'] = $order['qty'];
            $order_data[$i]['price']    = $order['price'];
            $order_data[$i]['name']     = $order['name'];
            $order_data[$i]['currency'] = "GBP";
            $order_data[$i]['sku']      = $order['pid'].",".$order['rowid'];
            $subtotal = $subtotal + ($order['price'] * $order['qty']);

            //dbdata for insert
            $insert_data[$i]['pid']     = $order['pid'];
            $insert_data[$i]['qty']     = $order['qty'];
            $insert_data[$i]['cid']     = $customer_id;
            $insert_data[$i]['price']   = $order['price'];
            $insert_data[$i]['currency']    = "GBP";
            $insert_data[$i]['method']  = "paypal";
            
            $i++;
        endforeach;
        
        $this->logger->info("order info: ".var_export($order_data, true));
        $additional_prices = array('subtotal' => $subtotal, 
                                    'currency' => 'GBP',
                                    'tax'       => '0.00',
                                    'shipping'  => '0.00'
                                );
        $additional_prices['total'] = $additional_prices['subtotal'] + $additional_prices['tax'] + $additional_prices['shipping'];

        //insert order details into DB for customer
        $this->load->model('ordersmodel');
        try {
            $result = $this->ordersmodel->create_order($insert_data, $additional_prices);
        } catch(Exception $e){
            $this->logger->info("Error: ".$e->getMessage());
        }
        
        if($result)
            //start processing paypal
            $this->process_paypal($order_data, $additional_prices);
        else
            redirect(base_url()."basket/checkout");

	}


	private function process_paypal($order_data=false, $additional_prices){
		$this->load->library("paypal");
		$this->load->library("payment");

        //get access token for paypal
        $paypal_token = $this->paypal->getAccessToken();
        if($paypal_token){
	        $this->payment->setValue("paypal_token", $paypal_token->access_token);
			$this->payment->setValue("pay_method", "paypal");

            //request paypal to create payment
            $payment_result = $this->paypal->createPayment($paypal_token->access_token, $order_data, $additional_prices);
            $this->payment->destroyValues();
            if($payment_result && $payment_result->links[1]->rel == "approval_url"){
                $this->payment->setValue("paypal_id", $payment_result->id);
                
                //redirect customer to get approval of sale
                redirect($payment_result->links[1]->href);
                // echo "<pre>";
                // print_r($payment_session);
				// echo "</pre>";
			}
	    }
    }

    //once customer approves, execute payment and save to DB
    public function paypal_callback(){
        $this->logger->info("Paypal callback received");
        $this->load->library("paypal");
        $this->load->library("payment");
        $payer_id       = $this->input->get("PayerID", true);
        $token          = $this->input->get("token", true);
        $access_token   = $this->payment->getValue("paypal_token");
        $id             = $this->payment->getValue("paypal_id");
        
        if($payer_id && $token){

            $result = $this->paypal->execute_payment($id, $payer_id, $access_token);
            //successfully executed the paypal payment
            if($result){
                //create a transaction in DB
                $this->load->model('ordersmodel');
                $order_id = $this->payment->getValue("order_id");
                $external_ref = array('paypal_id' => $result->id,
                                        'sales_id' => $result->transactions[0]->related_resources[0]->sale->id);
                $insertdata = array('oid'   => $order_id,
                                    'customer_id'   => $this->session->userdata("uid"),
                                    'subtotal'  => $result->transactions[0]->amount->details->subtotal,
                                    'total' => $result->transactions[0]->amount->total,
                                    'external_ref'  => json_encode($external_ref),
                                    'date_created'  => date('Y-m-d H:i:s'));
                $this->logger->info("inserting transaction data: ".var_export($insertdata, true));
                $trx_result = $this->ordersmodel->createTransaction($insertdata);
                if($trx_result)
					echo "transaction completed";
            }		
		}

	}

	
}
