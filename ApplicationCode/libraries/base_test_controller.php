<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * as this is an extension of the controller - this file must be explicitly included
 * at the base of the My_Controller.php file. 
 * 
 * We cannot simply include it in the application/libraries folder for autoloading as 
 * autoloading is actually executed within the Controller constructor in the codeigniter framework. 
 *
 * As it is the Controller we need to extend, we need to load this class sometime BEFORE controller
 * initialisation
 */

abstract class base_test_controller extends My_Controller
{
	public $layout = "unit_tests";
	private $test_description_template = "A description for this test has yet to be written";
	public $test_description = "";
	
	function __construct()
	{
		parent::__construct();
		$this->load->library('unit_test'); // Code igniter unit test framework
		$this->clear_test_description();
	}
	
	//
	// Results the test_description variable back to its initial value
	//
	function clear_test_description()
	{
		$this->test_description = $this->test_description_template;
	}

	//
	// Overridable in inheriting classes. This method is executed before any test methods are run
	//
	function fixture_setup(){}
	
	//
	// Overridable in inheriting classes. This method is executed after all test methods are run
	//
	function fixture_tear_down(){}
	
	//
	// Overridable in inheriting classes. This method is executed before each test method is run
	//
	function unit_setup(){}
	
	//
	// Overridable in inheriting classes. This method is executed after each test method is run
	//
	function unit_tear_down(){}
	

	//
	// Helper function to destroy and recreate a db containing setup data only (i.e. country and 
	// group information)
	//
	protected function recreate_the_database()
	{				
		log_test("Dropping database...");
		Doctrine::dropDatabases();
		
		log_test("Creating database...");
		Doctrine::createDatabases();
				
		log_test("Running initial schema setup sql");
		$this->execute_db_scripts_in_dir(APPPATH.'sql'.DIRECTORY_SEPARATOR.'setup_schema');

		log_test("Creating tables from models");
		Doctrine::createTablesFromModels();
		
		log_test("Populating additional fixture data");
		$this->execute_db_scripts_in_dir(APPPATH.'sql'.DIRECTORY_SEPARATOR.'setup_data');	
	}
	
	
	//
	// If no action is specified run all tests in the controller
	//
	public function index()
	{
		$this->run_tests();
	}

	//
	// Runs all methods in child classes prefixed with 'test_'
	//
	public function run_tests()
	{
		$classMethods = get_class_methods(get_class($this));

		$index = 0;
		
		//
		// Before running any tests - run the fixture_setup method if one is specified
		//
		if(method_exists($this, "fixture_setup")) $this->fixture_setup();

		foreach ($classMethods as $methodName)
		{
			if(preg_match("/^test_/",$methodName) == false) continue;
			
			$error_message = "";
			try
			{
				//
				// Before running the test - run the unit_setup method if one is specified
				//
				if(method_exists($this, "unit_setup")) $this->unit_setup();
				
				//
				// Execute the current test method
				//		
				$this->$methodName();
			
				//
				// After running the test - run the unit_tear_down method if one is specified
				//
				if(method_exists($this, "unit_tear_down")) $this->unit_tear_down();
			}
			catch(Exception $e)
			{
				$error_message = "Exception in unit test (".get_class($this)."->".$methodName."): ".$e->getMessage()."\nSTACK:\n".$e->getTraceAsString();
				log_error($error_message);
			}
			
			//
			// report all test results for this method only
			//			
			$results = array_slice($this->unit->result(),$index);
			$this->load->view("unit_tests", array(	"class_name" => get_class($this),
    												"method_name" => $methodName, 
													"test_description" => $this->test_description,
    												"test_results"=>$results,
													"error_message"=>str_replace("\n", "<br/>",$error_message)
												  ));	
			$index += count($results);
				
			$this->clear_test_description();			
		}
		
		//
		// After running all tests - run the fixture_tear_down method if one is specified
		//
		if(method_exists($this, "fixture_tear_down")) $this->fixture_tear_down();
			
	}
		
	//
	// executes all files in a given directory against the db
	//
	protected function execute_db_scripts_in_dir($path)
	{
		$scripts = dir_list($path);
		sort($scripts);

		foreach($scripts as $script)
		{
			$script_path = $path.DIRECTORY_SEPARATOR.$script;
				
			log_test("Executing db script: ".$script_path);
			$this->db->query($this->read_file_as_string($script_path));
		}
	}
	
	//
	// Reads the contents of a file and returns it as a string to the caller
	//
	protected function read_file_as_string($script_path)
	{
		$output="";
		$file = fopen($script_path, "r");

		while(!feof($file))
		{
			//
			//read file line by line into variable
			//
			$output = $output . fgets($file, 4096);
		}
		fclose ($file);

		return $output;
	}
}