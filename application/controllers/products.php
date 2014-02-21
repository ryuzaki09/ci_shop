<?php

class Products extends CI_Controller {
    
    public function __construct(){
        parent::__construct();
        $this->load->model('commonmodel');
        $this->load->model('productsmodel');
        $this->load->library('loadpage');
        $this->load->library('auth');
    }

    public function index($id=false){        
                
        $this->item($id);
        /*foreach($albums AS $folders){
            $where = array('albumID' => $folders['albumID']);
        }*/
    }

    public function item($id){

        if($this->input->post('add_basket') == "Add to Basket"){
            $rowID = $this->input->post('rowID', true);
            $pid = $this->input->post('pid', true)*1;
            $pname = $this->input->post('pname', true);
            $price = $this->input->post('price', true);

            //check for basket session
            $basket = is_basket();
            
            //echo $rowID;
            if(empty($basket)){	//if no basket session
                $basket_data = array('id' => $pid,
                                    'qty' => 1,
                                    'price' => $price,
                                    'name' => $pname
                                );
				//insert data into basket
                $data['rowID'] = $this->cart->insert($basket_data);
            
            } else { //if theres basket session
                //if the same product is there then add 1 to qty
                if(@$basket[$rowID]){
                    $basket_data = array('rowid' => $rowID, 'qty' => ($basket[$rowID]['qty']+1));

                    $this->cart->update($basket_data);
                    $data['rowID'] = $rowID;

                } else {
					//if product is not in the basket session then create one.
					$basket_data = array('id' => $pid,
                                        'qty' => 1,
                                        'price' => $price,
                                        'name' => $pname
                                        );
					//insert data into basket
                    $data['rowID'] = $this->cart->insert($basket_data);	
				}	
			
            }
		}

        $basket = is_basket();

        if($id){
            //if there is an album selected then select where clause
            $where = array('pid' => $id);
        } else {
            //if not then select most recent album
            $where = array('pid' => 1);
        }

        //get product item
        $data['product'] = $this->productsmodel->db_get_product($where);

        //if no rowID
        if(@$data['rowID']==""){

            if(!empty($basket)){  //if theres a basket session
				foreach ($this->cart->contents() as $items): 
					if($items['id'] == $data['product']->pid && $items['price'] == $data['product']->price && $items['name'] == $data['product']->name){
                        $data['rowID'] = $items['rowid'];

                    }

                endforeach;
            }
		}
		$data['pagetitle'] = "Product: ".$data['product']->name;
 
        $this->loadpage->loadpage('products/item', $data);
    }

    public function add_basket(){
		error_reporting(E_ALL);

        require_once($_SERVER['DOCUMENT_ROOT']."/dompdf/dompdf_config.inc.php");

        $html = $this->load->view('products/test','', true);
		//echo $html;

        $dompdf = new DOMPDF();
		$dompdf->load_html($html);
		$dompdf->render();
		$dompdf->stream("sample.pdf");
    }

    public function empty_basket(){

        if(is_basket()){
            $this->cart->destroy();
			redirect(base_url());
		}
    }

}

