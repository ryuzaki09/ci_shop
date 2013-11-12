<?php

class Shoppage extends CI_Controller {
    
    public function __construct(){
        parent::__construct();
        $this->load->library('loadpage');
        $this->load->library('auth');
		$this->load->model('commonmodel');
        $this->load->model('productsmodel');
    }
    
    public function index(){
		echo 4 + "4<br />";

        $this->load->model('carouselmodel');
		//carousel data	
		$data['carousel'] = $this->carouselmodel->alldata();
        //products data
        $data['products'] = $this->productsmodel->db_allproducts();
		
		//$html = "<img src='/images/test.jpg' alt='test' /><img src='/images/test2.jpg' alt='test2' /><img src='/images/test.jpg' alt='test' />";
		//preg_match_all('/<img[^>]+>/i',$html, $result);
		//$data['img_array'] = $result;
		$data['pagetitle'] = "Shop Longdestiny";
        $this->loadpage->loadpage('shop', $data);
    }
    
    private function addbasket(){
        
        
    }
	

    
}
?>
