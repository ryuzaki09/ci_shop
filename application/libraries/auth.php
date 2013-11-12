<?php

class Auth {    
    
    public function __construct(){
        
        $this->CI =& get_instance();        
        $this->is_logged_in();
    }
    
    function is_logged_in(){
        
        $userdata = $this->CI->session->userdata('is_logged_in');
        if (isset($userdata) && $userdata == true){
            return true;
        } else {
			$this->CI->load->helper('url');
			//check to make sure its not the admin login page and not ajax
			if(current_url() != base_url().'index.php?/admin/login' && ((!isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH']!='XMLHttpRequest')))) {    
            	redirect(base_url().'admin/login');
			}
        }
    }
	
	function is_basket(){
        
        $basketdata = $this->CI->session->userdata('basket');
        if (isset($basket) && $basket == true){
            return true;
        }else{
            return false;
        }
    }
    
}
?>
