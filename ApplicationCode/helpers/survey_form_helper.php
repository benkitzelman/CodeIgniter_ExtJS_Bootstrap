<?php

//
// Helpers are generally included in the config/autoload file. They can be accessed by a controller, or view.
// To access a helper in a layout, it must be explicitly loaded in the layout of interest
//

//
// Wraps any validation errors into a Error Panel div. For styling reasons, call this rather than
// CodeIgnitier's validation_errors() method directly
//
function validationErrorPanel()
{
	$errs = validation_errors();
	if(empty($errs)) return "";
	return "<div class='ErrorPanel'>".$errs."</div>";
}