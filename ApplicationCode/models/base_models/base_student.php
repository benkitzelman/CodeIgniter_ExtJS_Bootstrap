<?php
//
// Base Model Classes are responsible for defining the column / field mapping between db and domain layer.
// All db mapping code and basic model fields / properties should be done here.
// As such, this is also a good place to specify equality overrides.
//
// All models are loaded at runtime within the plugins/doctrine_pi.php bootstrapper and can be constructed
// without the need for explicit include statements
//
abstract class BaseStudent extends My_Model
{
	public function setTableDefinition() 
	{
		$this->hasColumn('title', 'string', 255);		
		$this->hasColumn('first_name', 'string', 255);
		$this->hasColumn('last_name', 'string', 255);
		$this->hasColumn('student_number', 'string', 255);
	}

	public function setUp() 
	{
		$this->setTableName('students');
	}	
	
	function equals($object)
	{
		if(parent::equals($object) == false) return false;
		
		if($object->title != $this->title) return false;
		if($object->first_name != $this->first_name) return false;
		if($object->last_name != $this->last_name) return false;
		if($object->student_number != $this->student_number) return false;
		
		return true;
	}
}