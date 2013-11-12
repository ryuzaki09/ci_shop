<?php

class Products extends CI_Controller {
    
    public function __construct(){
        parent::__construct();
        $this->load->model('commonmodel');
        $this->load->library('adminpage');
        $this->load->model('adminmodel');     
        $this->load->model('productsmodel');
		
		$this->auth->is_logged_in();
		
    }
    
   
    public function addnew(){
        
        $this->load->helper('form');
        $data['pagetitle'] = "Add new product";	

        //start upload process
        if($this->input->post('upload') == "Upload"){
            $name       = $this->input->post('name', true);
            $desc       = $this->input->post('desc', true);
            $price      = $this->input->post('price', true)*1;
            $category   = $this->input->post('category', true);
            $sub_cat    = $this->input->post('subcategory', true);
            //check all fields are entered
            if($name != "" && $desc != "" && $price !="" && $category !="" && $sub_cat != ""){
                //$data['message'] = "All fine";
                $files = $_FILES;
                $this->load->library('upload');

                $config['upload_path']   = './media/images/products/'; 
                $config['allowed_types'] = 'gif|jpg|png';
                $config['max_size']   = '8000'; //size in kilobytes
                $config['encrypt_name']  = true;

                $this->upload->initialize($config);
                
                Foreach($files AS $imgfile => $imgname){ //go through each img upload
                        if(!empty($imgfile)){ //check to see if it's empty                                
                            if($this->upload->do_upload($imgfile)){  //if not empty then upload
                                $imgdata = $this->upload->data(); //get encrypted file data and then insert the filename below
                                               
                                //display the name of the file before encryption
                                $data['imgfiles'][] = $imgname['name']." has been uploaded<br/>";
                                $imgs[] = $imgdata['file_name'];
                            } else {
                                $data['imgfiles'][] = $this->upload->display_errors();
                            }                               
                        } else {
                            $data['imgfiles'][] = "No image selected";
                        }
                }//end foreach                    
                $result = $this->productsmodel->db_insert_update_products(false, $name, $desc, $imgs[0], $imgs[1], $imgs[2], $imgs[3], $price, $category, $sub_cat);

            } else {
                $data['message'] = "Please make sure all fields are entered";
            }

        }//if post = upload
		
        $this->adminpage->loadpage('admin/products/upload', $data);
        
    }
    
    public function listing(){
        
        $this->load->library('pagination');
        $data['products'] = $this->productsmodel->db_allproducts();
        $total_rows = count($data['products']);
        
        $config['base_url'] = base_url().'admin/products/listing/';
        $config['total_rows'] = $total_rows;
        $config['per_page'] = 3; 
        
        $data['pagetitle'] = "Product List";
        $this->adminpage->loadpage('admin/products/list', $data);
            
    }
    
    function edit($id){ 
       $id = $id*1; 
       $where = array('pid' => $id);      
       if ($this->input->post('update') == "Update"){
           $name       = $this->input->post('name', true);
           $desc       = $this->input->post('desc', true);
           $price      = $this->input->post('price', true)*1;
           $category   = $this->input->post('category', true);
           $sub_cat    = $this->input->post('subcategory', true);
           
           $current_img = array('0' => $this->input->post('current_img1', true),
		   						'1' => $this->input->post('current_img2', true),
		   						'2' => $this->input->post('current_img3', true),
		   						'3' => $this->input->post('current_img4', true));
                         
           $img = array('0' => $_FILES['img1']['name'],
		   				'1' => $_FILES['img2']['name'],
		   				'2' => $_FILES['img3']['name'],
		   				'3' => $_FILES['img4']['name']);

           	$files = $_FILES;
           	$count=0;
			Foreach($files AS $images => $imgname){               
               
	           if ($img[$count] != ""){ //if there is a file to upload
	                   if($current_img[$count] != ""){ //check for a previous image
	                       if(file_exists($_SERVER['DOCUMENT_ROOT'].'/media/images/products/'.$current_img[$count])){
	                           //delete previous image
	                           unlink($_SERVER['DOCUMENT_ROOT'].'/media/images/products/'.$current_img[$count]);
	                       }
	                   }// end of deleting previous image
	                   $this->load->library('upload');
	                   $config['upload_path'] = $_SERVER['DOCUMENT_ROOT'].'/media/images/products/';
	                   $config['allowed_types'] = 'gif|jpg|png';
	                   $config['max_size']	= '5000';
	                   	                   
	                   $this->upload->initialize($config);
	                   	                   
	                   if($this->upload->do_upload($images)){  //upload new image
	                        $uploaded = $this->upload->data();
	                        $imgs[] = $uploaded['file_name']; // image filenames ready to update into DB                       
	                        
	                        $data['imgfiles'][] = $imgname['name']." has been uploaded";
	                   } else {                        
	                        $data['imgfiles'][] = $this->upload->display_errors();
	                   }            
	                   
	           } else { // no image to upload
	                                      
	                   $data['imgfiles'][] = "Product updated. You did not select an image to upload";
	                   //keep old image name 
	                   $imgs[$count] = $current_img[$count];              
	           }
			   $count++;          
			}
			//update record in the database after the upload
			$result = $this->productsmodel->db_insert_update_products($id, $name, $desc, $imgs[0], $imgs[1], $imgs[2], $imgs[3], $price, $category, $sub_cat);
       }// end of update
              
       $data['item'] = $this->productsmodel->db_get_product($where);
       //$data['imgs'] = $this->adminmodel->retrieve_fpphotos($id);       
       $data['edit'] = true;

       $data['pagetitle'] = "Edit | ".$data['item']->name;
       $this->adminpage->loadpage('admin/products/upload', $data);   
   }
   
   
    
    function album($id){
                  
        $id = $id*1;            
        
        $where = array('albumID' => $id);
        //get album name
        $data['album_name'] = $this->photomodel->get_photoalbum($where);
        $images = $this->photomodel->db_get_albumPhotos($where);
        
        $data['album_photos'] = $this->photomodel->db_get_albumPhotos($where);
        
        $data['pagetitle'] = "Edit | ".$data['album_name']->folder_name;
        
        $this->adminpage->loadpage('admin/products/photos', $data);
            
    }
    
               
    function delete_product(){        
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH']=='XMLHttpRequest')) {
            $id         = ($this->input->post('id', true)*1);
            $imgname    = $this->input->post('imgname', true);
            //$foldername = $this->input->post('foldername', true);
                    
            //get images
            //$where = array('pid' => $id);
            //$images = $this->photomodel->db_get_product($where);           
            //delete images            
            //if(is_array($images) && !empty($images)){
                //foreach($images AS $photos){
                    unlink($_SERVER['DOCUMENT_ROOT']."/media/images/products/".$imgname);
                //}
                //remove from database
                $result = $this->productsmodel->db_delete_product($id);
                
                echo ($result)
                    ? "true"
                    : "Cannot delete product";
            //}            
            
               
        }
    }
    
    function update_album(){
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH']=='XMLHttpRequest')) {
            $albumID = ($this->input->post('albumID', true)*1);
            $old_name = $this->input->post('old_album_name', true);
            $album_name = $this->input->post('new_album_name', true);
            
            //rename the folder on the server
            rename($_SERVER['DOCUMENT_ROOT']."/media/images/".$old_name, $_SERVER['DOCUMENT_ROOT']."/media/images/".$album_name);
            
            $result = $this->photomodel->db_update_album($albumID, $album_name);
            
            echo ($result)
                ? "true"
                : "Cannot update";
        }
        
    }
   
    function delete_photo(){
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH']=='XMLHttpRequest')) {
            $id = ($this->input->post('id', true)*1);
            $foldername = $this->input->post('foldername', true);
            $imgname = $this->input->post('imgname', true);

            $result = $this->photomodel->db_delete_albumphotos($id, true);

            if ($result){
                unlink($_SERVER['DOCUMENT_ROOT']."/media/images/".$foldername."/".$imgname);
                echo "true";
            } else {
                echo "Cannot delete photo";
            }
        }
    }
    
    function update_photo(){
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH']=='XMLHttpRequest')) {
            $id = ($this->input->post('id', true)*1);
            $title = $this->input->post('title', true);

            $result = $this->photomodel->db_insert_update_photo(false, $title, false, $id);
            
            echo ($result)
                ? "true"
                : "Cannot update";
        }
    }
    
}



