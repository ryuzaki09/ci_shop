<?php
class Productsmodel extends Commonmodel {
	
	public function __construct(){
		parent::__construct();
	}
	
	public function db_get_product($where){
		if(is_array($where) && !empty($where)){
			$this->db->where($where);

			$get_table = $this->table['products'];

			$result = $this->db->get($get_table);

			return ($result->num_rows()>0)
					? $result->row()
					: false;
		}
		return;
    }
	
	public function db_allproducts(){
		$result = $this->db->get($this->table['products']);
		
		return ($result->num_rows()>0)
				? $result->result_array()
				: false;
	}
	
	public function db_delete_product($pid){
		if(is_numeric($pid)){
			$this->db->where('pid', $pid);
			$this->db->delete($this->table['products']);
			
			return($this->db->affected_rows()>0)
					? true
					: false;
		}
		return;

    }
	
	public function addProductOption($data){
		if(is_array($data) && !empty($data)){
			$this->db->insert($this->table['p_options'], $data);

			return ($this->db->affected_rows()>0)
				? true
				: false;
		}

		return;
	}
	
	public function getProductOptions($pid){
		if(is_numeric($pid)){
			$this->db->where("products.pid", $pid);
			$this->db->join("product_options", "products.pid=product_options.pid", "left");

			$result = $this->db->get($this->table['products']);

			return ($result->num_rows()>0)
					? $result->result_array()
					: false;
		}
		return;
	}

	public function getProductStock($pid){
		if(is_numeric($pid)){
			$this->db->select("stock");
			$this->db->where("pid", $pid);

			$result = $this->db->get($this->table['products']);
			return ($result->num_rows())
					? $result->row()
					: false;
		}
		return;

	}

	public function removeOneStock($pid){
		if(is_numeric($pid)){
			$this->db->query("UPDATE products SET stock = (stock-1) WHERE pid=$pid");

			return ($this->db->affected_rows()>0)
					? true
					: false;
		}
		return;
	}

	public function deleteFromBasket($qty, $pid){
		if(is_numeric($qty) && is_numeric($pid)){
			$this->db->set("stock", "stock+$qty", false);
			$this->db->where("pid", $pid);
			$this->db->update($this->table['products']);
						
			return ($this->db->affected_rows() >0)
				? true
				: false;
		}
		return;
	}

}

