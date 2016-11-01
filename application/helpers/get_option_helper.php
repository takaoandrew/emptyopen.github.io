<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('get_option')) {

	function get_option( $option_name ) {
		  $CI =& get_instance();
          $settings = $CI->db->get_where("opts", array("option_name" => $option_name));

          if(count($settings->row())) return $settings->row('option_value'); 

          return false;
	}

}

?>