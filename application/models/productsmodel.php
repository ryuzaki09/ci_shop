<?php
class Productsmodel extends Commonmodel {
    
    public function __construct(){
        parent::__construct();
    }
    
    
    public function db_get_product($where){
        $this->db->where($where);
        
		$get_table = $this->table['products'];
        
        $result = $this->db->get($get_table);
        
        return ($result->num_rows()>0)
                ? $result->row()
                : false;
            
    }
    
    public function db_allproducts(){
        $result = $this->db->get($this->table['products']);
        
        return ($result->num_rows()>0)
                ? $result->result_array()
                : false;
    }
    
    public function db_delete_product($id){
        $this->db->where('pid', $id);
        $this->db->delete($this->table['products']);
        
        return(mysql_affected_rows()>0)
                ? true
                : false;      
                
    }
    
    public function db_insert_update_products($id=false, $name, $desc, $img1=false, $img2=false, $img3=false, $img4=false, $price, $category, $subcat, $stock){
        
        $data = array('name' =>$name,
                      'desc' => $desc,
                      'price' => $price,
                      'category' => $category,
                      'sub_cat' => $subcat,
					  'stock' => $stock);
                
        if($img1){
            $data['img1'] = $img1; 
        }
        if($img1){
            $data['img2'] = $img2; 
        }
        if($img1){
            $data['img3'] = $img3; 
        }
        if($img1){
            $data['img4'] = $img4; 
        }
        
        if($id){
            $this->db->where('pid', $id);
            $this->db->update($this->table['products'], $data);
        } else {
            $this->db->insert($this->table['products'], $data);
        }
        
        return ($this->db->affected_rows()>0)
                ? true
                : false;
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
	}

}

