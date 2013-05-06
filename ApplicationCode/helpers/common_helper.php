<?php
//
// Helpers are generally included in the config/autoload file. They can be accessed by a controller, or view.
// To access a helper in a layout, it must be explicitly loaded in the layout of interest
//



//
// Logging helper methods
//
function log_info($message)
{
	log_message("info", $message);	
}

function log_error($message)
{
	log_message("error", $message);	
}

function log_warning($message)
{
	log_message("warning", $message);	
}

function log_test($message)
{
	log_info( "UNIT TEST: ".$message);	
}


//
// Tests to see if a string is prefixed with the given string
//
function string_starts_with($string, $search) 
{ 
    return (strncmp($string, $search, strlen($search)) == 0); 
} 

//
// Gets the Redux profile of the user currently logged into the system
//
function current_user()
{	
	$CI =& get_instance();
	
	// return null if noone is logged in
	if($CI->redux_auth->logged_in() == false) return null;
		
	$profile = $CI->redux_auth->profile();	
	
	//
	// if logged in and there is no profile in db - log user out
	// NB: This should only accidentally happen in dev when we wipe the db WHILE a user is
	// logged in
	//
	if(empty($profile) && $CI->redux_auth->logged_in()) redirect_to("subscriber", "logout");
	
	return $profile;
}

//
// Returns true if the user is a member of the specified group. If a user is not given, the current user
// is assumed
//
function user_is_in_group($group_names, $user = null)
{	
	$user = is_null($user) ? current_user() : $user;	
	
	$group_list = explode(',', $group_names);
	
	foreach($group_list as $group_name)
	{
		//
		// If the group is public, then allow everyone to access it
		//	
		if(strcasecmp($group_name, "public") == 0) return true;
		
		//
		// If there is no user specified, loop through the rest of the groups incase any others
		// may be 'public'
		//
		if(is_null($user) == true) continue;
	
		//
		// If the user is in the specified group, then allow access
		//
		if(strcasecmp($group_name, $user->group) == 0) return true;
	}
	
	//
	// otherwise deny
	//
	return false;
}

//
// Lists all files in a given directory (Just the file name, not path + filename)
//
function dir_list ($directory)
{  
	$results = array();
	$handler = opendir($directory);

	//
	// keep going until all files in directory have been read
	//
	while ($file = readdir($handler))
	{
		if ($file != '.' && $file != '..') $results[] = $file;
	}
  
	closedir($handler);
	return $results;
}