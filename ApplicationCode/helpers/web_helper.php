<?php
//
// Helpers are generally included in the config/autoload file. They can be accessed by a controller, or view.
// To access a helper in a layout, it must be explicitly loaded in the layout of interest
//


function url_to($controller = "", $action = "", $params = array())
{		
	$url = base_url();
	if(empty($controller)) return $url;
		
	$url .= $controller."/".$action;
	if(empty($params) == false)	 $url .= "/".join($params, "/");
	
	return $url;
}

function redirect_to($controller = "home", $action="", $params = array())
{
	redirect(url_to($controller, $action, $params));	
}


function link_to($text = "", $controller = "", $action = "", $params = array(), $attributes = NULL)
{	
	$url = url_to($controller, $action, $params);
	if(empty($text)) $text = $url;

	return "<a href='".$url."' ".$attributes.">".$text."</a>";;
}