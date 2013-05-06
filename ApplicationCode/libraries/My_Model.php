<?php

//
// The base model class - note all My_Model implement a custom Comparable interface.
// Basically, this means that rather than using the usual '==' operator for object comparisons.
//
// All Models that need to be persisted to the db using Doctrine need to extend My_Model
// Any helper classes / frameworks (i.e. Redux) dont need to.
//
// This class is explicity included at runtime in the plugins/doctrine_pi.php bootstrapper
//
abstract class My_Model extends Doctrine_Record implements Comparable
{
	//
	// when comparing two My_Models, use the equals method. This method should be overridden
	// in extending classes to ensure that all relevant properties are considered when checking for 
	// equality. 
	// 
	// This was implemented as when using the '==', we would get false results due to some of the
	// bi-directional associations made in the domain (i.e. ActiveRecord references Respondants, but Respondants
	// also maintain a reference back to the parent ActiveRecord). I think this confused the '==' operator and
	// caused it to go into an endless loop. As this is the case, overriding equals generally only check object
	// identities, rather than the object itself. See BaseActiveRecord for an example
	//
 	function equals($object)
 	{ 		
 		if($object == null) return false;
 		if(is_a($object, get_class($this)) == false) return false;
 		if($object->id != $this->id) return false;
 		 		
 		return true;
 	}
}