<?php

class Products extends CI_Controller {

    public function __construct(){
        parent::__construct();
		$this->load->model('productsmodel');
		$this->load->library('loadpage');
		$this->load->library('auth');
    }

    public function index($id=false){

		$this->item($id);
    }

    public function item($id){

		if($this->input->post('add_basket') == "Add to Basket"){
			$rowID = $this->input->post('rowID', true);
			$pid = $this->input->post('pid', true)*1;
			$pname = $this->input->post('pname', true);
			$price = $this->input->post('price', true);

			//  remove one stock of product
			$this->productsmodel->removeOneStock($pid);

			//check for basket session
			$basket = is_basket();

			//echo $rowID;
			if(empty($basket)){ //if no basket session
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

		$where = array('pid' => $id);

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

    public function empty_basket(){
		$basket = is_basket();
		// echo "<pre>";
		// print_r($basket);
		// echo "</pre>";

		// $this->productsmodel->emptyBasket($data);
		
		//loop through basket of products to return the stock quantity
        if($basket){
			foreach($basket AS $rowId):
				$this->productsmodel->emptyBasket($rowId['qty'], $rowId['id']);
			endforeach;

            $this->cart->destroy();
			redirect(base_url());
		}
    }

}

