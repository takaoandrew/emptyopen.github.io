<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('site_settings')) {
	function site_settings() {
		  $CI =& get_instance();
          $settings = $CI->db->get("settings");
          return $settings->row(); 
	}
}

?>