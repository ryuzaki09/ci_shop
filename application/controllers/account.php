<?php 

class Account extends CI_Controller {
	
    public function __construct(){
	parent::__construct();
	$this->load->library('loadpage');
	$this->load->library('auth');
	$this->load->model('commonmodel');
	$this->load->model('usermodel');
    }
	
	
    public function profile(){
	if($this->auth->is_logged_in()){
	    $uid = $this->session->userdata('uid');
	    $data['userdata'] = $this->usermodel->db_get_userdetails($uid);
	    $data['pagetitle'] = "My Account";
	    $this->loadpage->loadpage('user/account', $data);
	} else {
	    redirect(base_url());
	}
		
    }
	
    public function edit(){
	if($this->auth->is_logged_in()){
	    $uid = $this->session->userdata('uid');
	    $data['userdata'] = $this->usermodel->db_get_userdetails($uid);
			
	    $data['pagetitle'] = "Edit Account Details";
	    $this->loadpage->loadpage('user/edit', $data);
			
	} else {
	    redirect(base_url());
	}
    }
	
	
    public function update_details(){
	if($this->input->post('update') == "Update"){
	    $db_data = array('firstname' => $this->input->post('firstname', true),
			    'lastname' => $this->input->post('lastname', true),
			    'address1' => $this->input->post('address1', true),
			    'address2' => $this->input->post('address2', true),
			    'postcode' => $this->input->post('postcode', true));
	    
	    $uid = $this->session->userdata('uid');
			
	    $result = $this->usermodel->db_update_userdetails($db_data, $uid);
            
	    if($result){
		//update the session details with the new data
		$userdetails = $this->session->userdata("user_details");
		$newdata = array("email" => $userdetails['email'],
                                "address" => $db_data['address1'].", ".$db_data['address2'],
                                "postcode" => $db_data['postcode']);
                $this->session->set_userdata("user_details", $newdata);
				$this->session->set_flashdata('message', 'Changes Updated');
				redirect(base_url().'account/profile');
	    } else {
		$this->session->set_flashdata('message', 'Cannot Update');
		redirect(base_url().'account/profile');
	    }
	}
		
    }
	
	
} 
