<?php
//
// Finders are extensions to the Doctrine_Table. They allow us to create additional, more complex
// DQL queries and are automatically created when calling:
// Doctrine::getTable('entity_name');
//
// PROVIDING their respective classes extend Doctrine_Table,
// AND they have the classname <entity_name>Table.
//
// in helpers/finders_helper are quick access methods which can be used in controller or views 
//
class StudentTable extends Doctrine_Table
{ 
	
}