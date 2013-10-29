<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Loadpage {
   
    
    public function __construct(){
        $this->CI =& get_instance();
    }
    
    function loadpage($page, $data=false)
    {          
        	
            $this->CI->load->view('header', $data);
            //$this->CI->load->view('leftnav');
            $this->CI->load->view($page);
            $this->CI->load->view('footer');
               
    }
    
    function set($type=false, $source=false){
        if ($type == 'css'){                       
            $data = "<link rel='stylesheet' type='text/css' href='".$source."' />\n";        
            
        }
        
        if ($type == 'js'){            
            $data = "<script type='text/javascript' src='".$source."'></script>\n";                    
        }
        
        return $data;
    }  

    
    
}//END OF CLASS


/* End of file Someclass.php */
?>