<?php

class Login extends CI_Controller{
    
    function __construct(){
        parent::__construct();
        $this->load->library('adminpage');
        $this->load->model('commonmodel');
    }
    
    function index(){
        
		$data['css'][] = $this->adminpage->set('css', '/js/bootstrap/css/bootstrap.css');
        $this->adminpage->loadpage('admin/login', $data, false);
        
    }
    
    function process_login(){
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH']=='XMLHttpRequest')) {    
        
        $username = $this->input->post('username', true);
        $password = $this->input->post('password', true);
        
        
        if($username != "" && $password != ""){        
            $this->load->model('adminmodel');
            $login = $this->adminmodel->adminlogin($username, $password);
        
            if ($login != false){
                $session_data= array('uname' => $username,
                                     'uid' => $login->id,
                                     'is_logged_in' => true);               
                
                $this->session->set_userdata($session_data);                
                
                echo "true";
            }else {
                echo "false";                
            }
            
        } else{
            echo "false";            
        }
    }
    }
    
    function logout(){
        
        $this->session->sess_destroy();
        redirect(base_url().'admin/login');
    }
    
}


?>
