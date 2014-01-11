<?php

class Ordersmodel extends Commonmodel {

	public function __construct(){
		parent::__construct();
        $this->load->model('commonmodel');
	}

	
	public function create_order($data, $additional_prices){
        if(is_array($data) && !empty($data) && is_array($additional_prices) && !empty($additional_prices)){
            $orderData = array("customer_id" => $data[0]['cid'],
                                "order_created" => date('Y-m-d H:i:s'),
                                "total_price"   => $additional_prices['total']
                            );

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

}
