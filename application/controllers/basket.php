<?php

class Basket extends CI_Controller {
	private $tax = "0.00";
	private $shipping = "0.00";
	
	public function __construct(){
		parent::__construct();
		$this->load->model('productsmodel');
		$this->load->library('loadpage');
	}
	
	public function index(){
		$this->shoppingbasket();
	}
	
	//Update the shopping basket at the basket page
	public function shoppingbasket(){
		$useremail = $this->session->userdata('user_details');
		
		/*
        if($this->input->post('update') == "Update Cart"){
			$i = 0;
            foreach($this->input->post() AS $postdata => $value):
				if(is_numeric($value['pid'])){
					$stock = $this->productsmodel->getProductStock($value['pid']);
					$this->logger->info("value: ".$value['pid']);
                	$data[] = $value;
				}

				$i++;
            endforeach;

			$this->logger->info("Post data: ".var_export($data, true));
            $this->cart->update($data);
        }
		*/
		$data['js'][] = $this->loadpage->set("js", "/js/basket.js");
		$data['pagetitle']  = "Shopping Basket";
		$this->loadpage->loadpage('basket/list', @$data);
	}
	
	public function removeProductFromBasket(){
		$rowId = $this->input->post("rowid", true);

		$this->logger->info("removing row id: ".$rowId);

		$cart_data = array();
		//loop through cart to build new cart excluding the the POST rowId product
		foreach($this->cart->contents() AS $products => $item):
			if($products != $rowId){
				// $this->logger->info("cart: ".$products);
				$cart_data[] = $item;
				$this->logger->info("match");
			} else {
				//when product is matched then restore qty back into DB
				$this->productsmodel->deleteFromBasket($item['qty'], $item['id']);
			}
		endforeach;
		
		$this->logger->info("data: ".var_Export($cart_data, true));
		if(!empty($cart_data))
			$this->cart->update($cart_data);
		else
			$this->cart->destroy();

		$total_items = $this->cart->total_items();
		$data['total_items'] = $total_items;

		$this->logger->info("cart: ".var_Export($this->cart->contents(), true));

		echo json_encode($data);
	}
	
	//confirmation page to checkout and require customer to be logged in.
	public function checkout(){
		
		$data['environment'] = (ENVIRONMENT == "development")
                            ? true
                            : false;
		
		$data['alt_address'] = $this->session->userdata("alternative_address");
		// var_dump($alt_address);
		
		if($this->auth->is_logged_in()){
			$this->load->model('usermodel');
			
			$uid = $this->session->userdata('uid');
			$data['userdata'] = $this->usermodel->db_get_userdetails($uid);
			
			$data['css'][] = $this->loadpage->set("css", "/css/jquery-ui-1.10.0.custom.min.css");
			$data['js'][] = $this->loadpage->set("js", "/js/jquery.validate.min.js");
				
			$data['pagetitle'] = "Confirmation Page";
			$this->loadpage->loadpage('basket/confirm', $data);
		} else {
			redirect(base_url().'user/login');
		}
		
	}
    
	/**
	 * process_checkout 
	 * This is when the customer clicks on the Proceed to Payment on the checkout page
	 * @access public
	 * @return void
	 */
	 public function process_checkout(){

		$alt_address = $this->session->userdata("alternative_address");
		$customer_details = $this->session->userdata("user_details");
		
		//if there is no alternative address then get current address
		if(!$alt_address['address'] && !$alt_address['postcode']){
			//check for current address - if there is none then redirect customer to edit account details page
			if(!$customer_details['address1'] || !$customer_details['address2'] || !$customer_details['postcode']){
				$this->session->set_flashdata("message", "Please complete your address details before proceeding");
				redirect("/account/edit");
			}

			$delivery_add = array("name" => $this->session->userdata("customer"),
									"address" => $customer_details['address1'].", ".$customer_details['address2'],
									"postcode" => $customer_details['postcode']
									);	
		} else
			$delivery_add = $alt_address;

		$this->logger->info("delivery address: ".var_export($delivery_add, true));
		
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
                                    'tax'       => $this->tax,
                                    'shipping'  => $this->shipping
                                );
		$additional_prices['total'] = $additional_prices['subtotal'] + $additional_prices['tax'] + $additional_prices['shipping'];

		//insert order details into DB for customer
        $this->load->model('ordersmodel');
		
		try {
			$result = $this->ordersmodel->create_order($insert_data, $additional_prices, $delivery_add);
		} catch(Exception $e){
			$this->logger->error($e->getMessage());
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
				$this->logger->info("redirecting customer to: ".$payment_result->links[1]->href);

				//redirect customer to get approval of sale
				redirect($payment_result->links[1]->href);

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

				//if transaction completed then redirect
				if($trx_result){
					$this->logger->info("Transaction Completed: ".$order_id);
					$this->cart->destroy(); //destroy the shopping cart session now that transaction is completed.
					redirect("/basket/orderComplete");
				} else {
					$this->session->set_flashdata("error_msg", "Transaction cannot be completed. Please try again later.");
					redirect("/basket/checkout");
				}
			}
		} //end of if theres a payer_id and token
	}

	//redirect here after transaction is completed
	public function orderComplete(){
		$this->load->library("payment");
		$paymentvalues = $this->payment->getAllValues();

		//check if theres any order/payment info first before showing the page otherwise redirect to homepage
		if(is_array($paymentvalues) && !empty($paymentvalues)){
			//send confirmation email
			$this->load->library('email');
			$useremail = $this->session->userdata('user_details');
			$this->logger->info("Sending Confirmation email to ".$useremail['email']);

			$this->email->from("noreply@shoplongdestiny.com");
			$this->email->to($useremail['email']);
			$this->email->subject("Your Order Confirmation");
			$this->email->message("Thank you for your order!");
			$this->email->send();

			$data['order_info'] = $paymentvalues; //assign order info to show on the view
			$this->payment->destroyValues(); //delete all payment info in the session

			$data['pagetitle'] = "Order Completed";

			$this->loadpage->loadpage("basket/order_complete", $data);

		} else {
			redirect(base_url());
		}

	}

}
