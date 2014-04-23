<?php

class Settings extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->library("adminpage");
		$this->load->model("adminmodel");

    }
    
    public function content(){

		if($this->input->post("save") == "Save"){
			$insertdata = array ("page_name" => $this->input->post("page_name", true),
								"page_content" => $this->input->post("page_content", true)
					);

			$result = $this->adminmodel->create_update_page_content($insertdata);
			echo ($result) ? "done!" : "failed";

		}

		//get list of all page Content
		$data['result'] = $this->adminmodel->getPageContentList();
		$data['pagetitle'] = "Page Content";

		$this->adminpage->loadpage("admin/settings/page_content", $data);
    }

    public function edit_page($page_id){

		if($this->input->post("save") == "Save"){
			$updatedata = array ("page_name" => $this->input->post("page_name", true),
								"page_content" => $this->input->post("page_content", true)
								);
			
			$result = $this->adminmodel->create_update_page_content($updatedata, $page_id);

		}

		$data['result'] = $this->adminmodel->getPageContent($page_id);
		$data['pagetitle'] = "Edit Page";

		$data['js'][] = $this->adminpage->set('js', '/js/ckeditor/ckeditor.js');
		$this->adminpage->loadpage("admin/settings/page_content", $data);

    }

    public function createPage(){


		$data['pagetitle'] = "Create Page";
        $data['js'][] = $this->adminpage->set('js', '/js/ckeditor/ckeditor.js'); 
		$this->adminpage->loadpage("admin/settings/page_content", $data);

    }

}
