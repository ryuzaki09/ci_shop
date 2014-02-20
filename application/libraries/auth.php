<?php

class Auth {    
    
    public function __construct(){
        
        $this->CI =& get_instance();        
        $this->is_logged_in();
    }
    
    public function is_logged_in(){
        $userdata = $this->CI->session->userdata('is_logged_in');
        if (isset($userdata) && $userdata == true){
            return true;
        } else {
            $this->CI->load->helper('url');
            //check to make sure its not the admin login page and not ajax then redirect back to login page if theres no session.
			// if(current_url() != base_url().'index.php?/admin/login' && ((!isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH']!='XMLHttpRequest')))) {    
            if($this->CI->uri->segment(1) == 'admin' && $this->CI->uri->segment(2) != 'login' && ((!isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH']!='XMLHttpRequest')))) {
                redirect(base_url().'admin/login');
            }
        }
    }
	
	public function is_basket(){
        
        $basketdata = $this->CI->session->userdata('basket');
        return (isset($basket) && $basket == true)
                ? true
                : false;
    }
    
}
?>
