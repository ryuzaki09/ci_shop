<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('test_method'))
{
    function test_method($var = '')
    {
        return $var;
    }   
}
	
	function is_basket(){	
		$CI =& get_instance(); //To access CodeIgniter's native resources within your library use the get_instance() function.
        return $CI->cart->contents();
	}
	
	
	
	
	
	
	





?>