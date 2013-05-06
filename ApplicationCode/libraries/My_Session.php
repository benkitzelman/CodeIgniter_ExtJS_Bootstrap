<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//
// A CodeIgniter override: The framework should automatically handle the detection of this
// override, and the instantiation and calling of this class. 
//
class My_Session extends CI_Session {
	
	/*
	 * The original session only allowed access to flash messages on postback
	 * which quite honestly blows. This override allows you to view quickflash data
	 * within the same request as it was written to the session.
	 * 
	 * param $quickflash - explicitly set to false if you want to use flash the codeigniter way
	 */
	function flashdata($key, $quickflash = true)
	{
		$quickflash_tag = $quickflash ? ":new:" : ":old:";		
		$flashdata_key = $this->flashdata_key.$quickflash_tag.$key;
		return $this->userdata($flashdata_key);
	}
}