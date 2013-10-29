<?php

class Profile extends CI_Controller {
    
    function __construct(){
        parent::__construct();
        $this->load->library('adminpage');
        $this->load->library('auth');
        $this->load->model('adminmodel');
    }
    
    
    function changepwd(){
        $login = $this->auth->is_logged_in();        
        if($login == true){
        
        $data['pagetitle'] = "Change Password";        
        $this->adminpage->loadpage('admin/profile', $data);
        
        } else {
            redirect(base_url().'admin/login');
        }
    }
    
    function process(){
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH']=='XMLHttpRequest')) {
            $data = array('pwd' => $this->input->post('pwd1', true));

            //retrieve username from session
            $username = $this->session->userdata('uname');
            $result = $this->adminmodel->changepwd($data, $username);

            echo ($result >= 1)
            ? "true"
            : "false";
        }
    }
}


?>
