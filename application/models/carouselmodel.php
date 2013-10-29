<?php
class Carouselmodel extends Commonmodel {
 
    function add_update_item($filename=false, $name, $desc, $price, $pos, $id=false){
        $data = array('name' => $name, 'desc' => $desc, 'price' => $price,'position' => $pos);
        
        if($filename){
        	$data['imgname'] = $filename;
        }		
		
		if($id){
			$this->db->where('id', $id);
			$result = $this->db->update($this->table['carousel'], $data);
		} else {
        	$result = $this->db->insert($this->table['carousel'], $data);
		}
        return(mysql_affected_rows()>0)
                ? true
                : false;
    }
    
    function alldata(){
        $this->db->order_by('position', 'desc');
        $result = $this->db->get($this->table['carousel']);
        
        return ($result->num_rows()>0)
                ? $result->result_array()
                : false;
    }
    
    function db_get_portfolio($id){
        $this->db->where('port_id', $id);
        
        $result = $this->db->get($this->table['portfolio']);
        
        return ($result->num_rows()>0)
                ? $result->row()
                : false;
        
    }
    
    function db_update_portfolio($id, $data){
        $this->db->where('port_id', $id);
        $result = $this->db->update($this->table['portfolio'], $data);
        
        return (mysql_affected_rows()>0)
                ? true
                : false;
        
    }
    
    function db_delete_carousel($id){
        $this->db->where('id', $id);
        $this->db->delete($this->table['carousel']);
        
        return (mysql_affected_rows()>0)
                ? true
                : false;
        
    }
    
}
?>
