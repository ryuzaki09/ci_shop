<?php

class Portfolio extends CI_Controller {
    
    function __construct(){
        parent::__construct();
        $this->load->model('commonmodel');
        $this->load->model('portfoliomodel');
    }
    
    
    function index(){
        $data['result'] = $this->portfoliomodel->alldata();
        
        $this->load->view('portfolio', $data);
    }
    
}
?>
