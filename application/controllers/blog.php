<?php

class Blog extends CI_Controller {
    
    //constructor is named the same as the class. it overrides the constructor contained the
    //parent class.
    function __construct() {
        //to fix this. manually call the constructor in the parent class.
        parent::__construct();
    }
    
    
    function index() {
        $pageinfo['title'] = "Array";
        $pageinfo['heading'] = "Foreach Loop";
        $pageinfo['heading2'] = "While Loop";
        
        $this->load->view('array', $pageinfo);
        $this->registerform();
    }
    
    
    function registerform(){
        $this->load->helper('form');
        $this->load->view('register');
        
    }
    
    
}

?>
