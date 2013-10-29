<?php

class Contact extends CI_Controller {
		
		
	function __construct(){
		parent::__construct();
		$this->load->library('loadpage');
	}		
		
		
	function index(){
		
		if($this->input->post('send', true) == "send"){
			$name = $this->input->post('name', true);
			$email = $this->input->post('email', true);
			$message = $this->input->post('message', true);
			$copy = $this->input->post('copy', true);
			
			$this->load->library('email');					
						
			$this->email->set_newline("\r\n");
			$this->email->set_mailtype("html");
						
		}
		
		
		$data['pagetitle'] = "Contact Us";
		$this->loadpage->loadpage('contact', $data);
	}
	
	
	
	function contact_msg(){
	    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH']=='XMLHttpRequest')) {
	        $this->load->helper('email');
	        $from = $this->input->post('name', true);
	        $email = $this->input->post('email', true);
	        $website = $this->input->post('website', true);
	        $msg = $this->input->post('msg', true);
	        $copy = $this->input->post('copy');
	        
	        if(valid_email($email)){
	            /*$this->load->library('email');                                
	            $this->email->from($email, $from);
	            $this->email->to('arlong2k8@googlemail.com'); 
	            if($copy == "mailcopy"){
	                $this->email->cc($email); 
	            }
	
	            $this->email->subject('Email from '.$from.' via Longdestiny.com');
	            if($website != ""){                
	                $this->email->message("<html><head></head><body><h2>Message: ".$msg."</h2></body></html>");                
	            } else {
	                $this->email->message($msg);	
	            }
	            
	            $this->email->send();            
	            echo "true";*/
	            
	            $to = "arlong2k8@googlemail.com";
	            $subject = "Message from Shop.LongDestiny.com";
	 				
	            $body = "Name : " . $from . "<br/>
	                     Email: " . $email . "<br/>
	                     Website: " . $website . "<br /><br />
	                     Message: " . $msg;
	            
	            $headers = "From: $email\r\n";
	            $headers .= "Content-type: text/html\r\n";
	            if ($copy != "") { $to = $to . "," . $email; }
	                                
	            if (mail($to, $subject, $body, $headers)) {
	   			echo "true";
	            } else {
	   			echo "Message delivery failed...";
	            }	
		        
	        } else {
	            echo "Invalid Email!";
	        }
	    }
    }
		
		
		
		
		
		
}
	