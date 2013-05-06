<?php
//
// This controller is designed to use the unit test framework to recreate the database (deletes ALL data)
// re-inserting baseline data (the minimum amount of data required to have an operational site) 
//
class ResetEnvironment extends base_test_controller
{
	function test_Recreate_data_base()
	{
		$this->test_description = "Drops and Recreates Database with relevant tables and base line data";
		$this->recreate_the_database();

		//
		// if no exception was thrown - assume the insertion was successful
		//
		$this->unit->run(true, true, "Database dropped and recreated");
			
	}

	function test_Insert_baseline_data()
	{
		$this->test_description = "Inserting baseline structural data required to provide a functioning website";
		//
		// Insert the baseline data ready to seed a blank, website ready db
		//
		$m = SampleStudent::michael();
		$m->save();
		
		$mo = SampleStudent::morris();
		$mo->save();
				
		$this->unit->run(true, true, "Persisted sample baseline data Students: ".$m->first_name." and ".$mo->first_name);	
	}
}
