<?php

class Ordersmodel extends Commonmodel {

	public function __construct(){
		parent::__construct();
	}

	public function create_order($data, $additional_prices){
        if(is_array($data) && !empty($data) && is_array($additional_prices) && !empty($additional_prices)){
            $orderData = array("customer_id" => $data[0]['cid'],
                                "order_created" => date('Y-m-d H:i:s'),
                                "total_price"   => $additional_prices['total']
                            );

			//insert into order table to create order no.
            $orderNumber = $this->create_orderNumber($orderData); 
            if($orderNumber){
                $this->load->library("payment");
                $this->payment->setValue("order_id", $orderNumber);
                $i = 0;
                $details = array();
                foreach($data AS $newdata):
                    $details[$i] = $newdata;
                    $details[$i]['order_no'] = "US000".$orderNumber;
                    $i++;
                endforeach;
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

    private function create_orderNumber($orderdata){
        $this->db->insert($this->table['orders'], $orderdata);
        
        return ($this->db->affected_rows()>0)
            ? $this->db->insert_id()
            : false;

    }


    public function createTransaction($data){
        
        $this->db->insert($this->table['trx'], $data);
		return ($this->db->affected_rows()>0)
				? true
				: false;
    }

	public function get_pending_orders(){
		$this->db->select('status, order_no, order_created, total_price');
		$this->db->from('orders');
		$this->db->join('order_details', 'orders.oid=order_details.oid');
		// $this->db->join('transactions', 'order_details.oid=transactions.oid');
		$this->db->group_by('order_no');

		$result = $this->db->get();

		return ($result->num_rows()>0)
				? $result->result_array()
				: false;
	}


	public function get_order_details($order_no){
		$this->logger->info("retrieving order details");
		$this->db->select('firstname, lastname, name, products.price, qty, cid, order_no, total, date_created, external_ref, method');
		$this->db->from('products');
		$this->db->join($this->table['o_details'], 'products.pid=order_details.pid');
		$this->db->join($this->table['trx'], 'order_details.oid='.$this->table['trx'].'.oid', 'left');
		$this->db->join('users', 'cid=uid');
		$this->db->where('order_no', $order_no);
		$result = $this->db->get();

		return ($result->num_rows()>0)
				? $result->result_array()
				: false;

	}

}
