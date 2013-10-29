<?php
class Usermodel extends Commonmodel {
    
    
    function __construct(){
        parent::__construct();
        
    }
    
    function db_get_user($email, $password){
        $this->db->where('email', $email);
        $this->db->where('password', $password);
        
        //if($table == "album"){
        $get_table = $this->table['users'];
        //}
        /*
        if($table == "photos"){
            $get_table = $this->table['albumPhotos'];
        }*/
        
        $result = $this->db->get($get_table);
        
        return ($result->num_rows()>0)
                ? $result->row()
                : false;
               
    }
	
	function check_existing($email){
		
		$this->db->where('email', $email);
		$result = $this->db->get($this->table['pending_users']);
		
		//if there is existing
		if($result->num_rows()>0){
			return TRUE;
		} else {
			$this->db->where('email', $email);
			$result2 = $this->db->get($this->table['users']);
			
			return ($result2->num_rows()>0)
				? TRUE
				: false;
			
		}			
			
	}
	
	function insert_new_pending_account($data){
		if(is_array($data) && !empty($data)){
			$this->db->insert($this->table['pending_users'], $data);
			
			return (mysql_affected_rows()>0)
				? TRUE
				: FALSE;
		}
		
	}
	
	function activate_account($where){
		$this->db->select('uid, email, password, firstname, lastname');
		$this->db->where($where);
		//retrieve data from pending users table
		$result = $this->db->get($this->table['pending_users']);
		//if user is found
		if($result->num_rows()>0){
			$data = $result->row();
			$insertdata =array('firstname' => $data->firstname,
								'lastname' => $data->lastname,
								'email' => $data->email,
								'password' => $data->password);
			//then insert the data into the users table
			$this->db->insert($this->table['users'], $insertdata);
			
			if (mysql_affected_rows()>0){
				//delete data from pending users
				$this->db->where('uid', $data->uid);
				$this->db->delete($this->table['pending_users']);
				
				return (mysql_affected_rows()>0)
						? TRUE
						: FALSE;
				
			} else {
				return false;
			}
				
		} else {
			return false;
		}
		
	}

	function get_email($email){
		$this->db->where('email', $email);
		$result = $this->db->get($this->table['users']);
		
		return ($result->num_rows()>0)
			? $result->row()
			: FALSE;
	}
	
	function insert_forgot_pwd($uid, $reset_code){
		$this->db->set('uid', $uid);
		$this->db->set('reset_code', $reset_code);
		
		$this->db->insert($this->table['forgot_pwds']);
		
		return (mysql_affected_rows()>0)
			? TRUE
			: FALSE;
	}

	function check_forgot_pwd_user($where){
		$this->db->where($where);
		
		$result = $this->db->get($this->table['forgot_pwds']);
		
		return ($result->num_rows()>0)
			? $result->row()
			: false;
	}
	
	function reset_password($forgot_id, $pwd, $uid){
		
		$this->db->set('password', $pwd);
		$this->db->where('uid', $uid);
		$this->db->update($this->table['users']);
		
		$this->db->where('uid', $uid);
		$this->db->delete($this->table['forgot_pwds']);
		
		return (mysql_affected_rows()>0)
			? TRUE
			: FALSE;
	}
	
	function db_get_userdetails($uid){
		$this->db->where('uid', $uid);
		
		$result = $this->db->get($this->table['users']);
		
		return ($result->num_rows()>0)
			? $result->row()
			: FALSE;
		
	}
	
	function db_update_userdetails($data, $uid){
		$this->db->where('uid', $uid);
		$this->db->update($this->table['users'], $data);
		
		return (mysql_affected_rows()>0)
			? TRUE
			: FALSE;
	}
	
}

?>