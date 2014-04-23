<?php

class Adminmodel extends Commonmodel {
        
    public function __construct(){
        parent::__construct();
    }
    
    public function adminlogin($username, $password){        
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
    
    public function retrieve_data($id){
        
        $this->db->where('id', $id);
                
        $result = $this->db->get($this->table['frontpage']);
        
        return ($result->num_rows() > 0)
			? $result->row()
			: false;
        
    }
	
	public function retrieve_user($id){
        
        $this->db->where('uid', $id);
                
        $result = $this->db->get($this->table['users']);
        
        return ($result->num_rows() > 0)
				? $result->row()
				: false;
        
    }
    
    
    public function changepwd($data, $where){
        $this->db->where('name', $where);
        return $this->db->update($this->table['adminusers'], $data);
        
    }
    
    public function create_update_page_content($data, $where=false){
	
		if($where){
			$this->db->where("id", $where);
			$this->db->update("page_content", $data);
		} else {
			$this->db->insert("page_content", $data);
		}

		return ($this->db->affected_rows()>0)
			? true
			: false;

    }

    public function getPageContentList(){
	
		$result = $this->db->get("page_content");

		return ($result->num_rows()>0)
			? $result->result_array()
			: false;

    }

    public function getPageContent($id){
	
		$this->db->where("id", $id);

		$result = $this->db->get("page_content");

		return ($result->num_rows()>0)
			? $result->row()
			: false;

    }
    
    
}
