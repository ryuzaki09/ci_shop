<?php 

class Account extends CI_Controller {
	
	function __construct(){
		parent::__construct();
		$this->load->library('loadpage');
		$this->load->library('auth');
		$this->load->model('commonmodel');
		$this->load->model('usermodel');
	}
	
	
	function profile(){
		if($this->auth->is_logged_in()){
			$uid = $this->session->userdata('uid');
			$data['userdata'] = $this->usermodel->db_get_userdetails($uid);
			$data['pagetitle'] = "My Account";
			$this->loadpage->loadpage('user/account', $data);
		} else {
			redirect(base_url());
		}
		
	}
	
	function edit(){
		if($this->auth->is_logged_in()){
			$uid = $this->session->userdata('uid');
			$data['userdata'] = $this->usermodel->db_get_userdetails($uid);
			
			$data['pagetitle'] = "Edit Account Details";
			$this->loadpage->loadpage('user/edit', $data);
			
		} else {
			redirect(base_url());
		}
	}
	
	
	function update_details(){
		if($this->input->post('update') == "Update"){
			$db_data = array('firstname' => $this->input->post('firstname', true),
							'lastname' => $this->input->post('lastname', true),
							'address1' => $this->input->post('address1', true),
							'address2' => $this->input->post('address2', true),
							'postcode' => $this->input->post('postcode', true));
			$uid = $this->session->userdata('uid');
			
			$result = $this->usermodel->db_update_userdetails($db_data, $uid);
			//echo $this->db->last_query();
			if($result){
				$this->session->set_flashdata('message', 'Changes Updated');
				redirect(base_url().'account/profile');
			} else {
				$this->session->set_flashdata('message', 'Cannot Update');
				redirect(base_url().'account/profile');
			}
		}
		
	}
	
	
} 