<?php
class Carousel extends CI_Controller {
    function __construct(){
        parent::__construct();
        $this->load->model('commonmodel');
        $this->load->library('adminpage');
        // $this->load->library('auth');                
        $this->load->model('carouselmodel');
        $this->auth->is_logged_in();        
    }
    
    function addnew(){
		if($this->input->post('add', true) =="Add to Carousel"){
			$name 	= $this->input->post('name', true);
			$desc 	= $this->input->post('desc', true);
			$price 	= $this->input->post('price', true);
			$pos 	= $this->input->post('position', true);
			
			//check for blank fields
			if($name !=""){
				$this->load->library('upload');
				$image = "image";

				$config['upload_path']   = './media/images/carousel/'; //if the files does not exist it'll be created
				$config['allowed_types'] = 'gif|jpg|png';
				$config['max_size']   = '8000'; //size in kilobytes                    
				$config['encrypt_name']  = true;

				$this->upload->initialize($config);

				if(!$this->upload->do_upload($image)){
					$data['message'] = $this->upload->display_errors();
				} else {
					$filedata = $this->upload->data();
					$result = $this->carouselmodel->add_update_item($filedata['file_name'], $name, $desc, $price, $pos);

					$data['message'] = "Item Added!";
				}

			} else {
				$data['message'] = "Please enter a name!";
			}
		}

		$data['pagetitle'] = "Carousel | Add New";

		$this->adminpage->loadpage('admin/carousel/add_edit', $data);
	
    }
    
    function listing(){
		$data['result'] = $this->carouselmodel->alldata();

		$data['pagetitle'] = "Carousel List";
		$this->adminpage->loadpage('admin/carousel/list', $data);
	
    }
    
    function edit($id){
            $id = $id*1;
            
            if($this->input->post('update', true) == "Update"){
                $update = array('port_title' => $this->input->post('title', true),
                                'port_link' => $this->input->post('link', true),
                                'position' => $this->input->post('position', true));
                $old_img = $this->input->post('old_image', true);
                
                $image = "image";
                $this->load->library('upload');
                
                $config['upload_path']   = './media/images/portfolio/'; //if the files does not exist it'll be created
                $config['allowed_types'] = 'gif|jpg|png';
                $config['max_size']   = '8000'; //size in kilobytes
                $config['encrypt_name']  = true;

                $this->upload->initialize($config);
                //echo $_SERVER['DOCUMENT_ROOT'].'/media/images/portfolio/'.$old_img;
                //if there is a file to upload
                if($this->upload->do_upload($image)){
                    if($old_img != ""){ //if there is an old image, check for image and delete
                        if(file_exists($_SERVER['DOCUMENT_ROOT'].'/media/images/portfolio/'.$old_img)){
                            unlink($_SERVER['DOCUMENT_ROOT'].'/media/images/portfolio/'.$old_img);
                        }
                    }
                    
                    $filedata = $this->upload->data();
                    $update['port_img'] = $filedata['file_name'];                    
                }
            
                //perform the update
                $result = $this->portfoliomodel->db_update_portfolio($id, $update);
                $data['message'] = ($result)
                                    ? "Updated!"
                                    : "Cannot update";
            }
            
            $data['result'] = $this->portfoliomodel->db_get_portfolio($id);
            
            $data['pagetitle'] = "Edit | ".$data['result']->port_title;
            $this->adminpage->loadpage('admin/portfolio/edit', $data);
            
    }
    
	function update_carousel(){
		if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH']=='XMLHttpRequest')) {
			$id = ($this->input->post('id', true)*1);	
			$name = $this->input->post('name', true);
			$desc = $this->input->post('desc', true);
			$price = $this->input->post('price', true);	
			$pos = $this->input->post('pos', true);
			
			$result = $this->carouselmodel->add_update_item(false, $name, $desc, $price, $pos, $id);
			if($result){
				echo "true";
			} else {
				echo "Cannot Update";
			}
		}
	}
	
	
    function delete_carousel(){
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH']=='XMLHttpRequest')) {
            $id = ($this->input->post('id', true)*1);
            $old_img = $this->input->post('old_img', true);
            
            if($old_img != ""){
                if(file_exists($_SERVER['DOCUMENT_ROOT'].'/media/images/carousel/'.$old_img)){
                    unlink($_SERVER['DOCUMENT_ROOT'].'/media/images/carousel/'.$old_img);
                }
            }
            
            $result = $this->carouselmodel->db_delete_carousel($id);
            
            echo ($result)
                    ? "true"
                    : "Cannot delete";

        }
    }
    
}
?>
