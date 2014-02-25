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
    
    function db_delete_albumphotos($id, $singlephoto=false){        
        if ($singlephoto){
            $this->db->where('pid', $id);
        } else {
            $this->db->where('albumID', $id);
        }
        
        $this->db->delete($this->table['albumPhotos']);
        
        return (mysql_affected_rows()>0)
            ? true
            : false;
    }
    
    function db_update_album($albumid, $album_name){
        $this->db->set('folder_name', $album_name);
        $this->db->where('albumID', $albumid);
        $result = $this->db->update($this->table['photoalbum']);
        
        return (mysql_affected_rows()>0)
                ? true
                : false;
        
    }
    
    public function db_insert_update_products($id=false, $name, $desc, $img1=false, $img2=false, $img3=false, $img4=false, $price, $category, $subcat){
        
        $data = array('name' =>$name,
                      'desc' => $desc,
                      'price' => $price,
                      'category' => $category,
                      'sub_cat' => $subcat);
                
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
            $result = $this->db->update($this->table['products'], $data);
        } else {
            $result = $this->db->insert($this->table['products'], $data);
        }
        
        return ($result >=1)
                ? true
                : false;
    }
    
    public function db_get_albumPhotos($where){
        $this->db->where($where);
        $result = $this->db->get($this->table['albumPhotos']);
        
        return ($result->num_rows()>0)
                ? $result->result_array()
                : false;
    }
    
}

