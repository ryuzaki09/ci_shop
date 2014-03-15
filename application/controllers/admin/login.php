<?php

class Login extends CI_Controller{
    
    public function __construct(){
        parent::__construct();
        $this->load->library('adminpage');
    }
    
    public function index(){
	$data['pagetitle'] = "Admin Login";
        $this->adminpage->loadpage('admin/login', $data, false);
        
    }
    
    public function process_login(){
	if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH']=='XMLHttpRequest')) {    
	    $this->logger->info("processing login");
			
	    $username = $this->input->post('username', true);
	    $password = $this->input->post('password', true);
			
	    if($username != "" && $password != ""){        
		$this->load->model('adminmodel');
		$login = $this->adminmodel->adminlogin($username, $password);
			
		if ($login != false){
		    $this->logger->info("login details found");
		    $session_data= array('uname' => $username,
					 'uid' => $login->id,
					 'is_logged_in' => true);               
					
		    $this->session->set_userdata($session_data);                
					
		    echo "true";
		} else {
		    $this->logger->info("login details not found");
		    echo "false";                
		}
				
	    } else {
		echo "false";            
	    }
	}
    }
    
    public function logout(){
        
        $this->session->sess_destroy();
        redirect(base_url().'admin/login');
    }
    
}


?>
