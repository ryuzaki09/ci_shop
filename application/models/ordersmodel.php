<?php

class Ordersmodel extends Commonmodel {
    private $order_no_prefix = "US000";

    public function __construct(){
	parent::__construct();
    }

    /**
     * create_order 
     * 
     * @param mixed $data 
     * @param mixed $additional_prices 
     * @access public
     * @return void
     */
    public function create_order($data, $additional_prices){
	if(is_array($data) && !empty($data) && is_array($additional_prices) && !empty($additional_prices)){
	    $orderData = array("customer_id" => $data[0]['cid'],
	    			"order_created"	=> date('Y-m-d H:i:s'),
				"total_price"	=> $additional_prices['total']
				);

	    //insert into order table to create order no.
	    $order_id = $this->create_orderNumber($orderData);
	    if($order_id){
		$order_no = $this->order_no_prefix.$order_id; 
                $this->logger->info("Order No: ".$order_no);

                //set order info into session
		$this->load->library("payment");
		$this->payment->setValue("order_id", $order_id);
		$this->payment->setValue("order_no", $order_no);		

		$i = 0;
				
		$details = array();
		//loop through each product to give order number
		foreach($data AS $newdata):
		    $details[$i] = $newdata;
		    $details[$i]['oid']         = $order_id;
		    $details[$i]['order_no']    = $order_no;
		
		    $i++;
		endforeach;
		
		//and then insert into DB for records	
		$this->db->insert_batch($this->table['o_details'], $details);
		if($this->db->affected_rows()>0)
		    return true;
		else
		    throw new Exception("Cannot insert order details");
		
	    } else {
		throw new Exception("Cannot create order number");
	    }
	}
    }

    /**
     * create_orderNumber 
     * 
     * @param mixed $orderdata 
     * @access private
     * @return void
     */
    private function create_orderNumber($orderdata){
        $this->db->insert($this->table['orders'], $orderdata);
        
        return ($this->db->affected_rows()>0)
            ? $this->db->insert_id()
            : false;

    }

    /**
     * createTransaction 
     * 
     * @param mixed $data 
     * @access public
     * @return void
     */
    public function createTransaction($data){
        
        $this->db->insert($this->table['trx'], $data);
	return ($this->db->affected_rows()>0)
		? true
		: false;
    }

    /**
     * get_pending_approved_orders 
     * 
     * @param mixed $status 
     * @access public
     * @return void
     */
    public function get_pending_approved_orders($status){
        $this->logger->info("Getting $status orders");
	$this->db->select('orders.oid, status, order_no, order_created, total_price, external_ref');
	$this->db->from('orders');
	$this->db->join('order_details', 'orders.oid=order_details.oid');
	$this->db->join('transactions', 'orders.oid=transactions.oid');
	$this->db->where('status', $status);
	$this->db->group_by('order_no');

	$result = $this->db->get();

	return ($result->num_rows()>0)
		? $result->result_array()
		: false;
    }


    /**
     * get_order_details 
     * 
     * @param mixed $order_no 
     * @access public
     * @return void
     */
    public function get_order_details($order_no){
	$this->logger->info("retrieving order details");
	$this->db->select('firstname, lastname, name, orders.oid, products.price, qty, cid, order_no, total, order_created, external_ref, method');
	$this->db->from('products');
	$this->db->join($this->table['o_details'], 'products.pid=order_details.pid');
	$this->db->join('orders', 'orders.oid=order_details.oid');
	$this->db->join($this->table['trx'], 'order_details.oid='.$this->table['trx'].'.oid', 'left');
	$this->db->join('users', 'cid=uid');
	$this->db->where('order_no', $order_no);
	$result = $this->db->get();

	return ($result->num_rows()>0)
		? $result->result_array()
		: false;

    }

    /**
     * admin_approve_order 
     * 
     * @param mixed $oid 
     * @access public
     * @return void
     */
    public function admin_approve_order($oid){
	$data = array('status' => 'approved');

	$this->db->where('oid', $oid);
	$this->db->update('orders', $data);

	return ($this->db->affected_rows()>0)
		? true
		: false;

    }
    
    /**
     * insertVoucher 
     * 
     * @param mixed $data 
     * @access public
     * @return void
     */
    public function insertVoucher($data){

	if(is_array($data) && !empty($data)){
	    $this->db->insert('vouchers', $data);

	    return ($this->db->affected_rows()>0)
		    ? true
		    : false;
	}

    }


    public function getVoucherList(){
	$result = $this->db->get('vouchers');

	return ($result->num_rows()>0)
		? $result->result_array()
		: false;
    }
}
