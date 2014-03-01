<?php

class Home extends CI_Controller {    
    
    public function __construct(){
        parent::__construct();
        $this->load->library('adminpage');
	$this->load->model('commonmodel');
	$this->auth->is_logged_in();
        
    }
    
    public function index(){        
        
        $this->adminpage->loadpage('admin/adminpage');
        
    }
    
    public function menusetup(){		
		
	if($this->input->post('add_menu') == "Add Menu"){			
					
	    $linkname = $this->input->post('linkname', true);
	    $parent = $this->input->post('parentmenu', true);
	    $parentID = ($this->input->post('parent_id', true)*1);
	    $linkurl = $this->input->post('linkurl', true);
	    
	    //link name must not be blank
	    //also if it is parent then url must be blank or
	    // if it is not parent then parent_id and url must not be blank 
	    if($linkname != "" && $parent =="Yes" && $linkurl =="")  {
		    
		    $result = $this->commonmodel->db_insert_menu($linkname);
						    
		    $data['message'] = ($result)
			    ? "Parent Menu Added!"
			    : "Failed to add Parent Menu!";				
	    //Add submenu - check inputs	
	    } elseif($linkname != "" && $parent =="No" && $linkurl != "" && $parentID >0){
		    
		    $result = $this->commonmodel->db_insert_menu($linkname, $parentID, $linkurl);
		    
		    $data['message'] = ($result)
			    ? "<div class='alert alert-success'>Sub Menu Added!</div>"
			    : "<div class='alert alert-danger'>Failed to add Sub Menu!</div>";
	    } else {
		    $data['message'] = "Cannot Add. Please try again!";
	    }			
			
	}
	
	//get parent menus
	$data['parentmenus'] = $this->commonmodel->db_get_parentmenus();
	
	$data['pagetitle'] = "Admin Menu Setup";
	
	$this->adminpage->loadpage('admin/menusetup', $data);		
	    
    }

    public function menulist(){				
	    
	$data['pagetitle'] = "Admin Menu List";		
	$this->adminpage->loadpage('admin/menusetup', $data);
    }
    
    
    public function update_menu(){
	if($this->input->post('update_menu') == "Update"){
		
	    $parentmenu_id 	= ($this->input->post('parentmenuid', true)*1);
	    $submenu_id		= ($this->input->post('menuid', true)*1);
	    
	    //check if parent id or submenu id is submitted and build array to update	
	    if($parentmenu_id>0){
		$menu_id = $parentmenu_id;
		$updatedata = array('link_name' => $this->input->post('parentname', true));
	    } elseif($submenu_id>0){
		$menu_id = $submenu_id;	
		$updatedata = array('link_name' => $this->input->post('subname', true),
				    'url' => $this->input->post('suburl', true));
		    
	    }			
				    
	    $result = $this->commonmodel->db_update_submenu($updatedata, $menu_id);
	    
	    if($result)
		$this->session->set_flashdata('message', '<div class="alert alert-success">Changes updated!</div>');
	     else 
		$this->session->set_flashdata('message', '<div class="alert alert-danger">Cannot Update!</div>');
	    
	    
	    redirect(base_url().'admin/home/menulist');
	}
						    
    }

    public function delete_menu(){
	if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH']=='XMLHttpRequest')) {
		$menu_id = $this->input->post('menu_id', true);
		$parentid = $this->input->post('parent_id', true);
		
		if(isset($menu_id) && is_numeric($menu_id) && ($menu_id >0)){
			$result = $this->commonmodel->db_delete_menu(false, $menu_id);
		} elseif(is_numeric($parentid) && ($parentid >0)){ //check id is a number
			$result = $this->commonmodel->db_delete_menu($parentid);
		}
		
		echo ($result)
			? "true"
			: $this->db->last_query();			
	}
	    
    }
	
    public function addstyle(){
		
	    if($this->input->post('save', true) == "Save Style"){
		    
		$style_name = $this->input->post('style_name', true);
		
		if($style_name != ""){
		
		    $insertdata = array('style_name' => $style_name,
					'background_color' => $this->input->post('bg_color', true),
					'position' => $this->input->post('bg_position', true));
								
		    $this->load->library('upload');
		    $image = "bg_file";

		    $config['upload_path']   = './media/images/background/';
		    $config['allowed_types'] = 'gif|jpg|png';
		    $config['max_size']   = '8000';                
		    $config['encrypt_name']  = true;

		    $this->upload->initialize($config);
				    //if cannot upload image
		    if(!$this->upload->do_upload($image)){
			$data['message'] = $this->upload->display_errors();
		    } else {
			$filedata = $this->upload->data();                    
			$insertdata['image'] = $filedata['file_name'];
					
		    }
			    
		    $result = $this->commonmodel->db_addstyle($insertdata);
		    if($result){
			$this->session->set_flashdata('message', 'Style Saved!');
			redirect(base_url().'admin/home/stylelist');
		    } else {
			$data['message'] = "Cannot Add Style";
		    }				
							    
		} else {
		    $data['message'] = "A style name must be entered!";
		}
		    
	    }
	    
	    $data['pagetitle'] = "Add Global Style";
	    
	    $this->adminpage->loadpage('admin/style/addstyle', $data);
	    
    }
	
    public function stylelist(){
		
	    $data['stylelist'] = $this->commonmodel->db_get_styles();
	    
	    $data['pagetitle'] = "Style List";
	    
	    $this->adminpage->loadpage('admin/style/list', $data);
    }
	
    public function edit_style($id){
		
	if ($this->input->post('update') == "Update" && is_numeric($id)){
	    $stylename   = $this->input->post('style_name', true);
	    $bg_color    = $this->input->post('bg_color', true);
	    $bg_pos   	= $this->input->post('bg_position', true)*1;
           
	    $current_img = $this->input->post('oldfile', true);
                         
	    $newimg = $_FILES['bg_file']['name'];           
                      
	    $count=0;     
               
	    if ($newimg != ""){ //if there is a file to upload
		if($current_img != ""){ //check for a previous image
		    if(file_exists($_SERVER['DOCUMENT_ROOT'].'/media/images/background/'.$current_img)){
                           //delete previous image
                           unlink($_SERVER['DOCUMENT_ROOT'].'/media/images/background/'.$current_img);
                   }
                }// end of deleting previous image
		$this->load->library('upload');
                $config['upload_path'] = $_SERVER['DOCUMENT_ROOT'].'/media/images/background/';
                $config['allowed_types'] = 'gif|jpg|png';
                $config['max_size']	= '8000';
                   	                   
                $this->upload->initialize($config);
                   	                   
                if($this->upload->do_upload($images)){  //upload new image
		    $uploaded = $this->upload->data();
                    $imgs[] = $uploaded['file_name']; // image filenames ready to update into DB	                        
                        
                    $data['message'] = $imgname['name']." has been uploaded";
                } else {                        
                    $data['message'] = $this->upload->display_errors();
                }            
                   
	    } else { // no image to upload
                                      
                $data['message'] = "Product updated. You did not select an image to upload";
                //keep old image name 
                $imgs = $current_img;                   
	    }
	    $count++;			
			//update record in the database after the upload
	    $result = $this->commonmodel->db_update_style($id, $stylename, $bg_color, $bg_pos);
       	}// end of update
		
	$data['style'] = $this->commonmodel->db_get_styledata($id);
		
	$data['pagetitle'] = "Edit Style";
	$this->adminpage->loadpage('admin/style/addstyle', $data);
		
    }
      
    
    public function menu_sorting(){
		
	if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH']=='XMLHttpRequest')) {
		$updateRecordsArray     = $this->input->post('parent' ,true);
			
		if ($this->input->post('action') == "updateRecordsListings"){			
		    $listingCounter = 1;
				
		    foreach ($updateRecordsArray as $recordIDValue):				
			$this->commonmodel->db_sort_menu_position($listingCounter, $recordIDValue);					
			$listingCounter = $listingCounter + 1;
		    endforeach;									
		}					
	}
	    
    }
}

