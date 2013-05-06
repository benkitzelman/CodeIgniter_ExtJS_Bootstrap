<?php

//
// A CodeIgniter override: The framework should automatically handle the detection of this
// override, and the instantiation and calling of this class. 
//
abstract class My_Controller extends Controller
{	
	// used in the Yield hook to determine which layout to use
	public $layout = "default";
	
	// The default page title to displayed when an action doesn't otherwise set one
	public $page_title = "My Pages";
	public $redux_auth_model = null;
	
	function __construct()
	{
		parent::__construct();
		
		//
		// Since implementing Doctrine, we must explicitly include the redux model
		// into the base controller, as we can no longer autoload it 
		//
		$this->redux_auth_model = new redux_auth_model();	
		
		//
		// wrap all error reports written to the screen in an error div
		//
		if(isset($this->form_validation) == true) $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
	}
	
	//
	// This function is called BEFORE any action is processed in the controller
	// it can be overridden in any inheriting controller - make sure that if overriding
	// parent::before_action() is called in the overriding method
	//
	function before_action()
	{
		
	}
	
	//
	// This function is called AFTER any action is processed in the controller
	//
	function after_action()
	{
		
	}
	//
	// Short cut function - I got sick of referencing the form_validation object
	//
	protected function set_form_rule($fieldName, $englishName, $rule)
	{
		$this->form_validation->set_rules($fieldName, $englishName, $rule);
	}
	
	//
	// Short cut function - ensures all flash messages are wrapped in a display div
	// before writing to screen.
	//
	protected function set_flash($message = "", $is_error_msg = false)
	{
		//
		// css flag - sets an additional css class to the flash div to assist in visually 
		// distinguishing between error messages and success messages. See assets/css/default.css
		// for implementation of this display class
		//
		$css_flag = $is_error_msg ? "failure" : "success";		
		$this->session->set_flashdata('message', '<div class="flash '.$css_flag.'">'.$message.'</div>');
	}		
}

//
// We have to load all extending types at the base of this file as they need to be included at the same time the 
// Zark_surveys_controller is at execution. The reason for this is that CodeIgniter.php is responsible for creation
// and calling of controller actions, so all base controllers extending the My_Controller need to be
// accessible when CodeIgniter.php calls the relevant action: cf %framework_dir%/codeigniter/CodeIgniter.php lines 148 - 201
//
require_once(APPPATH.DIRECTORY_SEPARATOR."libraries".DIRECTORY_SEPARATOR."base_test_controller.php");