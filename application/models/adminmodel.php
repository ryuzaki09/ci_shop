<?php

class Adminmodel extends Commonmodel {
        
    function __construct(){
        parent::__construct();
    }
    
    function adminlogin($username, $password){        
        $this->db->select('id');
        $this->db->where('name', $username);
        $this->db->where('pwd', $password);
        $result = $this->db->get($this->table['adminusers']);
                
        if ($result){
			return ($result->num_rows()>0)
                ? $result->row()
                : false;
        } else {
            return false;
        }        
    }
    
    function retrieve_data($id){
        
        $this->db->where('id', $id);
                
        $result = $this->db->get($this->table['frontpage']);
        
        return ($result->num_rows() > 0)
        ? $result->row()
        : false;
        
    }
	
	function retrieve_user($id){
        
        $this->db->where('uid', $id);
                
        $result = $this->db->get($this->table['users']);
        
        return ($result->num_rows() > 0)
        ? $result->row()
        : false;
        
    }
    
    function retrieve_fpphotos($id, $foldername=false){
        $this->db->where('windowID', $id);
        
        if($foldername){
            $this->db->where('foldername', 'thumbs');
            $result = $this->db->get($this->table['fpphotos']);
            return ($result->num_rows() > 0)
                    ? $result->result_array()
                    : false;
            
        } else {
            $this->db->where('foldername', 'frontpage');
            
            $result = $this->db->get($this->table['fpphotos']);
            
            return ($result->num_rows()>0)
                    ? $result->row()
                    : false;
        }          
                
    }
    
    function update_fpphotos($id, $imgname){
        $this->db->where('windowID', $id);
        $this->db->set('imgname', $imgname);
        
        return $this->db->update($this->table['fpphotos']);
        
    }
    
    function update_window($id, $data){
        $this->db->where('id', $id);
        return $this->db->update($this->table['frontpage'], $data);
    }
    
    function delete_window($id){
        $this->db->where('id', $id);
        $result = $this->db->get($this->table['frontpage']);
        
        if ($result){
            $result = $result->row();            
               //delete from thumb folder
               unlink($_SERVER['DOCUMENT_ROOT'].'/media/images/frontpage/'.$result->image);            
        }
        
        $this->db->where('id', $id);
        $result2 = $this->db->delete($this->table['frontpage']);       
        
        if(mysql_affected_rows() > 0){
            
            return ($this->__delete_fpphotos($id))
                   ? true
                   : "no_images_to_delete";
            
        } else {
            return false;
        }
        
    }
    
    function __delete_fpphotos($id){
        $this->db->where('windowID', $id);
        $result = $this->db->get($this->table['fpphotos']);
        
        if ($result){
            $result = $result->result_array();
            foreach($result AS $img){                
               //delete from thumb folder
               unlink($_SERVER['DOCUMENT_ROOT'].'/media/images/frontpage/thumb/'.$img['imgname']);                
            }
            $this->db->where('windowID', $id);
            $result2 = $this->db->delete($this->table['fpphotos']);
            return (mysql_affected_rows()>0)
                    ? true
                    : false;                  
            
        } else {
            return false;
        }
    }
    
        
    function changepwd($data, $where){
        $this->db->where('name', $where);
        return $this->db->update($this->table['adminusers'], $data);
        
    }
    
    function fetch_albums(){
        $result = $this->db->get($this->table['photoalbum']);
        return ($result >= 1)
            ? $result->result_array()
            : false;
    }
    
    function add_new_album($foldername){
        $this->db->set('folder_name', $foldername);        
        return $this->db->insert($this->table['photoalbum']);
    }

    public function create_update_page_content(){


    }

    public function getPageContentList(){
	
	$result = $this->db->get("page_content");

	return ($result->num_rows()>0)
		? $result->result_array()
		: false;

    }
    
    
}
?>
