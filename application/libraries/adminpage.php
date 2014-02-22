<?php

class Adminpage {

    public function __construct(){
        $this->CI =& get_instance();
        $this->CI->load->model('commonmodel');
    }
    
    public function loadpage($page, $data=false, $adminmenu=true){

            //get navigation menus
            $data['menu_array'] = $this->__nav_menus();
            //echo $this->CI->db->last_query();
            $data['title'] = "LongDestiny CMS";
            $this->CI->load->view('admin/adminheader', $data);
            if($adminmenu == true) {
                $this->CI->load->view('admin/adminmenu');
                $this->CI->load->view('admin/adminmenu2', $data);
            }
            $this->CI->load->view($page, $data);
            $this->CI->load->view('admin/adminfooter');

    }
    
    public function set($type=false, $source=false){
        if ($type == 'css')
            $data = "<link rel='stylesheet' type='text/css' href='".$source."' />\n";
        
        if ($type == 'js')
            $data = "<script type='text/javascript' src='".$source."'></script>\n";

        return $data;
    }
	
    private function __nav_menus(){
        $left_menu = $this->CI->commonmodel->db_get_left_menus();
        $sub_leftmenu = $this->CI->commonmodel->db_get_left_menus($submenu=true);

        $menu_count=0;
        while($menu_count < count($left_menu)): //loop through parent
	    $menu_array[$menu_count] = $left_menu[$menu_count]; //build into new array
			
	    $subcount=0;
	    while($subcount < count($sub_leftmenu)): //loop through sub menus
		//echo $left_menu[$i]['parent_id']." ".$sub_leftmenu[$y]['parent_id']."<br />";
		if($left_menu[$menu_count]['parent_id'] == $sub_leftmenu[$subcount]['parent_id']){
    					
		    $menu_array[$menu_count]['submenu'][] = $sub_leftmenu[$subcount]; // build submenu into new array
		} 
		$subcount++;
				
	    endwhile;
	    
	    $menu_count++;
	endwhile;
		
		return $menu_array;
	}
    

}
?>
