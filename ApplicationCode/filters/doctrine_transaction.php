<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');


//
// Filters are a way of executing code before and after action methods are called.
// This filter is responsible for wrapping all called actions in a Doctrine Transaction
// to ensure that all db operations are handled ACID'ly - and that db errors are handled gracefully.
//
// For full instructions on how filters work
// http://codeigniter.com/wiki/Filters_system/
//
class doctrine_transaction_filter extends Filter {
	private $controllerObj = null;

	function __construct($config = array())
	{
		parent::__construct($config);
		$this->controllerObj = &get_instance();
	}
	 
	function before()
	{		
		log_info('TX: Setting up Doctrine Transaction BEFORE '.$this->controller.' -> '.$this->method);
		Doctrine_Manager::connection()->beginTransaction();
		
		//
		// Call the before_action method if one exists on the controller
		//
		if(method_exists($this->controllerObj, "before_action") == true) $this->controllerObj->before_action();
	}

	function after()
	{		
		try
		{
			//
			// Call the after_action method if one exists on the controller
			//
			if(method_exists($this->controllerObj, "after_action") == true) $this->controllerObj->after_action();
			log_info('TX: Committing Doctrine Transaction AFTER '.$this->controller.' -> '.$this->method);
			Doctrine_Manager::connection()->commit();
		}
		catch(Exception $e)
		{
			log_error("TX: Could not commit Doctrine Transaction: ".$e->getMessage()."\nSTACK:".$e->getTraceAsString());
			@Doctrine_Manager::connection()->rollback();
		}
	}
}
?>