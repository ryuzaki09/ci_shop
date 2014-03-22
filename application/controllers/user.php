<?php

class User extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->library('loadpage');
        $this->load->library('auth');
        $this->load->model('commonmodel');
        $this->load->model('usermodel');
    }

    public function login(){
        
        //login is pressed
        if($this->input->post('submit') =="Login"){            
            $email = $this->input->post('email', true);
            $pwd = ($this->input->post('password', true));
            
            $this->load->helper('email');
            
            if(valid_email($email)){
                
                $result = $this->usermodel->db_get_user($email, $pwd);
                if($result){
                    $address = implode(",", array($result->address1, $result->address2));
                    $session_data = array
				    ('customer' => $result->firstname." ".$result->lastname,
					'uid' => $result->uid,
					'is_logged_in' => true,
					'user_details' => array
							    ('email' => $email,
							     'address' => $address,
							     'postcode' => $result->postcode
							    )
                                    );

                    $this->session->set_userdata($session_data);

                    redirect(base_url());

                } else {
                    @$data['message'] = "Incorrect Email or Password";
                }
            } else {
                @$data['message'] = "Email address invalid";
            }
             
        }//end if

        $data['pagetitle'] = "Customer Login";
        $this->loadpage->loadpage('user/login', $data);

    }

    public function register(){
    	$register_success = false;

        $this->load->helper('form');
	$this->load->library('form_validation');

        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[users.email]'); //checks for duplicate email in DB
	$this->form_validation->set_rules('firstname', 'Firstname', 'trim|required');
	$this->form_validation->set_rules('lastname', 'Lastname', 'trim|required');
	$this->form_validation->set_rules('password1', 'Password', 'trim|required|matches[password2]|md5');
	$this->form_validation->set_rules('password2', 'Password Confirmation', 'trim|required');
	$this->form_validation->set_error_delimiters('<div class="error block200 go_left">', '</div>');
		
	//if found duplicate email address, displays this error message
	$this->form_validation->set_message('is_unique', 'This email address already exists!');
		
	//create account button is pressed
	if($this->input->post('create') == "Create Account!"):
	    if($this->form_validation->run() == False){

	    } else {
                $this->load->helper('string');
                $verification_code = random_string('unique');
                $email = $this->input->post('email', true);

                //check for existing account
                $result = $this->usermodel->check_existing($email);
		//if there is no existing account
		if(!$result){
		    $insertdata = array('email' => $email,
					'password' => $this->input->post('password1', true),
					'firstname' => $this->input->post('firstname', true),
					'lastname' => $this->input->post('lastname', true),
					'activation_code' => $verification_code);

                    $result = $this->usermodel->insert_new_pending_account($insertdata);

                    if(!$result){
                        $data['message'] = "Register Failed!";

		    } else {
			//get ID of last insert 
			$maildata['uid'] = $this->db->insert_id();
			$maildata['code'] = $verification_code;
			$maildata['email_title'] = "Account Activation";
			$this->load->library('email', $config);					
						
			$this->email->set_newline("\r\n");
			$this->email->set_mailtype("html");
						
			//put data into email
			$email_message = $this->load->view('user/htmlemail', $maildata, true);
						
			$this->email->from('arlong2k8@googlemail.com', 'Longdestiny');
			$this->email->to($email);
			$this->email->subject('Account Activation');
			$this->email->message($email_message);
			//$this->email->message('Thank you registering! Please click on the link below to activate your account. <br />http://shop.longdestiny.com/user/verify_email/'.$uid.'/'.$verification_code.' ');
			//$this->email->set_alt_message('Thank you registering! Please click on the link below to activate your account. <br />http://shop.longdestiny.com/user/verify_email/'.$uid.'/'.$verification_code.' ');
                        $this->email->send();

                        $register_success = true;
                    }
                } else {
                    $data['message'] = "Account already exists!";
                }
            }
        endif; //end submit create account
		
		//if register form completed then display register success
        if($register_success){
            $data['pagetitle'] = "Register Success!";
				
	    $this->loadpage->loadpage('user/registersuccess', $data);
		//display normal register form	
        } else {
	    $data['pagetitle'] = "Register New Account";
		
	    $this->loadpage->loadpage('user/register', $data);
	}
    }
	
	
    public function verify_email($uid, $activation_code){
	//check for empty data
	if(is_numeric($uid) && $uid != "" && $activation_code != ""){
	    $where = array('uid' =>$uid, 'activation_code' => $activation_code);
		
	    $result = $this->usermodel->activate_account($where);
	    if($result){
		$data['pagetitle'] = "Account Activated!";
		$data['successpage'] = "account activated";
		$this->loadpage->loadpage('user/registersuccess', $data);
	    //if there is no user	
	    } else {
		redirect(base_url());
	    }
			
	} else {
	    redirect(base_url());
	}
    }
	
    public function forgot_password(){
        
        if($this->input->post('send_email') == "Send Email"){
            $email = $this->input->post('email', true);
            $this->load->helper('email');
            //check if valid email address
            if(valid_email($email)){
                //get user via email address
                $result = $this->usermodel->get_email($email);
                //if found
                if($result){
                    $this->load->helper('string');
                    $maildata['reset_code'] = random_string('unique');
		    $maildata['uid'] = $result->uid;
		    $result2 = $this->usermodel->insert_forgot_pwd($maildata['uid'], 
								    $maildata['reset_code']);
    
                    if($result2){
                        $this->load->library('email');	
			$this->email->set_newline("\r\n");
			$this->email->set_mailtype("html");
						
			$maildata['email_title'] = "Reset Password";
			$maildata['page'] = "forgot_password";
			//put data into email
			$email_message = $this->load->view('user/htmlemail', $maildata, true);
						
			$this->email->from('arlong2k8@googlemail.com', 'Longdestiny');
			$this->email->to($email);
			$this->email->subject('Password Reset');
			$this->email->message($email_message);
			$this->email->send();
						
			$data['message'] = "Email Sent! Please check your email and follow the instructions to reset your password.";
		    }
		//$data['message'] = "Found match!";
		} else {
		    $data['message'] = "Our records could not find a match to this email address. Please try again!";
		}
            } else {
            	$data['message'] = "Please enter a valid email address";
            }
	}

	$data['pagetitle'] = "Forgot Password";
	$data['loginpage'] = "forgotpassword";
		
	$this->loadpage->loadpage('/user/login', $data);
    }

    public function reset_password($uid, $reset_code){

	$data = array('uid' => $uid, 'reset_code' => $reset_code);
	
	$result = $this->usermodel->check_forgot_pwd_user($data);
		
	if($result){
			
	    $data['id'] = $result->id;
	    $data['pagetitle'] = "Reset Password";
			
	    $this->loadpage->loadpage('user/resetpwd', $data);
	} else {
	    redirect(base_url());
	}
		
    }
	
    public function change_password(){
		
	if($this->input->post('password_submit') == "Submit New Password!"){
	    $forgot_id = $this->input->post('forgot_id', true)*1;
	    $uid = $this->input->post('uid', true)*1;
	    $code = $this->input->post('code', true);
	    $pwd1 = $this->input->post('pwd1', true);
	    $pwd2 = $this->input->post('pwd2', true);
			
	    //check blank password fields
	    if($pwd1 != "" && $pwd2 !=""){
		//check if they match
		if($pwd1 == $pwd2){
		    $result = $this->usermodel->reset_password($forgot_id, md5($pwd1), $uid);
					
		    if($result){
			$data['page'] = "reset_success";
			$data['pagetitle'] = "Password Changed!";
						
			$this->loadpage->loadpage('user/resetpwd', $data);
		    }
					
		} else {
		    $this->session->set_flashdata('error', 'Passwords do not match!');
		    redirect(base_url().'user/reset_password/'.$uid.'/'.$code);
		}
				
	    } else {
		$this->session->set_flashdata('error', 'Please enter both password and confirm password fields.');
		redirect(base_url().'user/reset_password/'.$uid.'/'.$code);
	    }
	} else {
	    redirect(base_url());
	}
		
    }
	
	
    public function logout(){
        
        $this->session->sess_destroy();
        redirect(base_url());
    }
    
    
}
