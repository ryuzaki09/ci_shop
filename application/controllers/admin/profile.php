<?php

class Profile extends CI_Controller {
    
    public function __construct(){
        parent::__construct();
        $this->load->model('commonmodel');
        $this->load->model('adminmodel');
        $this->load->library('adminpage');
    }
    
    
    function changepwd(){
        
        $data['pagetitle'] = "Change Password";        
        $this->adminpage->loadpage('admin/profile', $data);
        
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
