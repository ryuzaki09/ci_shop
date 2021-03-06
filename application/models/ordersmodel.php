<?php

class Ordersmodel extends Commonmodel {
    private $order_no_prefix = "US000";
	const STATUS_REFUND = "refunded";
	const STATUS_APPROVED = "approved";
	const STATUS_PENDING = "pending";

    public function __construct(){
		parent::__construct();
    }

    /**
     * create_order 
     * 
     * @param mixed $data array
     * @param mixed $additional_prices array
     * @param mixed $delivery_info  array
     * @access public
     * @return boolean
     */
    public function create_order($data, $additional_prices, $delivery_info){
		if(is_array($data) && !empty($data) && is_array($additional_prices) && !empty($additional_prices) && is_array($delivery_info)){
			$orderData = array("customer_id" => $data[0]['cid'],
								"order_created"	=> date('Y-m-d H:i:s'),
								"delivery_address" => json_encode($delivery_info),
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
		throw new Exception("Please check parse values");
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
     * get_orders - pending, approved 
     * 
     * @param mixed $status 
     * @access public
     * @return void
     */
    public function get_orders($status){
        $this->logger->info("Getting $status orders");
		$this->db->select('orders.oid, status, order_no, order_created, orders.delivery_address, date_created, total_price, currency, external_ref');
		$this->db->from('orders');
		$this->db->join('order_details', 'orders.oid=order_details.oid');
		$this->db->join('transactions', 'orders.oid=transactions.oid');
		$this->db->where('status', $status);

		if($status == self::STATUS_REFUND){
			$this->db->like("external_ref", "refund");
		}
		$this->db->order_by("date_created", "desc");

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
    public function admin_approve_disapprove_order($oid, $status){
		$data = array('status' => $status);

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
    
    public function getCustomerOrders($uid, $limit=false, $offset=false, $total=false){
		//if selecting total then just select the count
		if($total == "total")
			$this->db->select("count(*) AS count");
		else
			$this->db->select("orders.order_created, status, order_details.order_no, delivery_address,
							order_details.price, qty, currency, products.name");

		$this->db->join("order_details", "orders.oid=order_details.oid");
		$this->db->join("products", "order_details.pid=products.pid");
		$this->db->where("customer_id", $uid);
		$this->db->order_by("orders.oid", "desc");

		if($limit)
			$this->db->limit($limit, $offset);
		
		$result = $this->db->get("orders");

		return ($result->num_rows()>0)
			? $result->result_array()
			: false;

    }

	public function refundsale($data=array()){
		if(is_array($data) && !empty($data)){
			$this->logger->info("creating transaction for refund: ".var_export($data, true));
			
			$this->db->insert("transactions", $data);

			if($this->db->affected_rows()>0){
				$this->logger->info("refund transaction created");

				$this->db->set("status", "refunded");
				$this->db->where("oid", $data['oid']);
				$this->db->update("orders");

				if($this->db->affected_rows()>0)
					return true;
				else
					throw new Exception("Cannot update order status to refund: ".$data['oid']);
			}				
			else
				throw new Exception("Cannot insert refund transaction: ".var_export($data, true));

		}

	}
    
}
