<?php

class Commonmodel extends CI_Model {
    public $table = array('users' => 'shoplongdestiny.users',
                        'adminusers' => 'shoplongdestiny.adminusers',
                        'products' => 'shoplongdestiny.products',
                        'carousel' => 'shoplongdestiny.carousel',
                        'pending_users' => 'shoplongdestiny.pending_users',
                        'forgot_pwds' => 'shoplongdestiny.forgotten_passwords',
                        'menus' => 'shoplongdestiny.admin_menu',
                        'style' => 'shoplongdestiny.global_settings',
                        'orders'    => 'shoplongdestiny.orders',
                        'o_details' => 'shoplongdestiny.order_details',
                        'trx'       => 'shoplongdestiny.transactions'
                       );

    
	
    public function db_insert_menu($linkname, $parentID=false, $linkurl=false){
				
	//if there is no parent id		
	if(!$parentID){
	    //then get the max parent id and add 1 to create a new parent id and insert into DB
	    $this->db->select_max('parent_id');			
	    $id_result = $this->db->get($this->table['menus']);
	    
	    $max_parent_id = $id_result->row();
	    $new_parent_id = ($max_parent_id->parent_id +1); //add 1 to the max parent id					
	    $insertdata = array('parent_id' => $new_parent_id, 'link_name' => $linkname);
		
	} else {
	    //if there is a parent ID then select the max submenu id and add 1 to create a new submenu and insert into DB
	    $this->db->select_max('sub_id');
	    $this->db->where('parent_id', $parentID);
	    $subid_result = $this->db->get($this->table['menus']);
	    
	    $max_sub_id = $subid_result->row();
	    $new_sub_id = ($max_sub_id->sub_id + 1);
	    
	    $insertdata = array('parent_id' => $parentID, 
				'link_name' => $linkname, 
				'sub_id' => $new_sub_id, 
				'url' => $linkurl);
		
	}
		
	$result = $this->db->insert($this->table['menus'], $insertdata);
		
	return ($this->db->affected_rows()>0)
		? TRUE
		: false;		
		
    }
	
    public function db_get_parentmenus(){
	$this->db->select('parent_id, link_name');
	$this->db->where('sub_id', null);
	
	$result = $this->db->get($this->table['menus']);
		
	return ($result->num_rows()>0)
		? $result->result_array()
		: false;
		
	}


    public function db_get_left_menus($submenu=false){
	//if submenu is true then get records where sub id is not empty
	$where = ($submenu)
		? "sub_id IS NOT NULL"
		: "sub_id IS NULL";
		
	$this->db->where($where);
	$this->db->order_by('order_no');
		
	$result = $this->db->get($this->table['menus']);
		
	return ($result->num_rows()>0)
		? $result->result_array()
		: false;
	}
	
	public function db_sort_menu_position($position, $id){
		$this->db->set('order_no', $position);
		$this->db->where('id', $id);
		
		$this->db->update($this->table['menus']);
		
	}
	
	
    public function db_update_submenu($data, $id){
		if(is_array($data) && !empty($data)){
			$this->db->where('id', $id);
			$result = $this->db->update($this->table['menus'], $data);
			
			return ($this->db->affected_rows()>0)
				? TRUE
				: false;	
			
		}
    }

	
    public function db_delete_menu($parent_id=false, $menu_id=false){
		if($parent_id){
			$this->db->where('parent_id', $parent_id);
		} elseif($menu_id) {
			$this->db->where('id', $menu_id);
		}
		
		$this->db->delete($this->table['menus']);
		
		return ($this->db->affected_rows()>0)
			? TRUE
			: false;
    }
	
    public function db_addstyle($insertdata){
	    
		if(is_array($insertdata) && !empty($insertdata)){
			
			$result = $this->db->insert($this->table['style'], $insertdata);
			
			return ($this->db->affected_rows()>0)
				? TRUE
				: false;
			
		}
    }

    public function db_get_styles(){
		$result = $this->db->get($this->table['style']);
		
		return ($result->num_rows()>0)
			? $result->result_array()
			: false;
    }
	
    public function db_get_styledata($id){
		$this->db->where('id', $id);
		
		$result = $this->db->get($this->table['style']);
		return ($result->num_rows()>0)
			? $result->row()
			: false;
	    
    }

	public function getPageContent($page){


	}

}
?>
