<?php

class Settings extends CI_Controller {

    public function __construct(){
	parent::__construct();
	$this->load->library("adminpage");
	$this->load->model("adminmodel");

    }
    
    public function content(){

	//get list of all page Content
	$data['result'] = $this->adminmodel->getPageContentList();
	$data['pagetitle'] = "Page Content";

	$this->adminpage->loadpage("admin/settings/page_content", $data);
    }
}
