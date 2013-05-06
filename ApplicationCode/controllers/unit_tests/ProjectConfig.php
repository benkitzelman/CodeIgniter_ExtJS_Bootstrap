<?php
class ProjectConfig extends base_test_controller {
		
	function set_up()
	{
		$tz_set = date_default_timezone_set("Australia/Brisbane");
	}
	
	function test_List_constants()
	{
		$this->test_description = "Determines is expected constants have been defined";
		
		$this->unit->run(true, defined("BASEPATH"), "BASEPATH = ".BASEPATH);
		$this->unit->run(true, defined("APPPATH"), "APPPATH = ".APPPATH);
		$this->unit->run(true, defined("CONTROLLER_FILE_PATH"), "CONTROLLER_FILE_PATH = ".CONTROLLER_FILE_PATH);
		$this->unit->run(true, defined("LIB_FILE_PATH"), "LIB_FILE_PATH = ".LIB_FILE_PATH);		
	}
	
	function test_Code_igniter_overriding_classes()
	{		
		$this->test_description = "Ensures all classes overriding CodeIgniter base classes have been successfully loaded into the project and are being used";
		
		$this->unit->run(true, is_subclass_of($this, "My_Controller"), "Custom Controller is used");
		$this->unit->run("My_Loader", get_class($this->load), "Custom Loader is used");
		$this->unit->run(false, is_null($this->session), "Session exists");
		$this->unit->run("My_Session", get_class($this->session), "Custom Session is used");
	}
	
	function test_Redux_is_enabled()
	{
		$this->test_description = "Ensures that the system has correctly configured the Redux Authentication framework - and it is available for use";
		
		$this->unit->run(false, is_null($this->redux_auth), "base controller contains Redux library");
		$this->unit->run(false, is_null($this->redux_auth_model), "base controller contains Redux model");
	}
	
	function test_Doctrine_is_enabled()
	{
		$this->test_description = "Ensures that the system has correctly configured the Doctrine Framework - and it is available for use";
		
		$path = Doctrine_Core::getPath();		
		$this->unit->run(false, empty($path), "Can access Doctrine core");
	}
	
	function test_change_locale_time()
	{
		$this->test_description = "Sets the timezone to French Timezone";	

		$tz_set = date_default_timezone_set("Europe/Paris");
		
		$local_time = mktime();
		$gmt_from_local = gmdate("r", $local_time);
		$local_from_local = date("r", $local_time);
		
		$this->unit->run(true, $tz_set, "Set Timezone did not fail");
		$this->unit->run(false, strpos($local_from_local, "+1000") > 0, "Time string [$local_from_local] is not local time");
	}
	
	function test_convert_gmt_to_local_and_back()
	{
		$this->test_description = "Converts between GMT and Local Times";
		$local_time = mktime();
		$gmt_from_local = gmdate("r", $local_time);
		$local_from_local = date("r", $local_time);
		
		$local_time = mktime();
		$gmt_from_local = gmdate("r", $local_time);
		$local_from_local = date("r", $local_time);
		
		$gmt_time = gmmktime();
		$gmt_from_gmt = gmdate("r", $gmt_time);
		$local_from_gmt = date("r", $gmt_time);
		
		$this->test_description = "Ensure that PHP uses dates correctly";
		$this->unit->run(true, $local_from_local != $gmt_from_local, "Different Local and GMT strings have been created");
		$this->unit->run($gmt_from_local, $gmt_from_gmt, "GMT date formatting are the same");
		$this->unit->run($local_from_local, $local_from_gmt, "Local date formatting are the same");
		
		//
		// Parse back from a local string to utc
		//
		$local_time_2 = strtotime($local_from_local);
		$gmt_from_local_2 = gmdate("r", $local_time_2);
		$this->unit->run($gmt_from_local, $gmt_from_local_2, "Local time string parses to gmt time");
		
		//
		// Parse back from a gmt string to local
		//
		$gmt_time_2 = strtotime($gmt_from_local);
		$local_from_gmt_2 = date("r", $gmt_time_2);
		$this->unit->run($local_from_gmt, $local_from_gmt_2, "GMT time [$gmt_from_local] string parses to local time [$local_from_gmt_2]");
	}
	
}