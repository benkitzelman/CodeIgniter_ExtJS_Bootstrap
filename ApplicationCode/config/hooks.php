<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/general/hooks.html
|
*/

//
// Filters are a way of executing code before and after action methods are called.
// This filter is responsible for wrapping all called actions in a Doctrine Transaction
// to ensure that all db operations are handled ACID'ly - and that db errors are handled gracefully.
//
// For full instructions on how filters work
// http://codeigniter.com/wiki/Filters_system/
//
$hook['post_controller_constructor'][] = array(
                                'class'    => '',
                                'function' => 'pre_filter',
                                'filename' => 'init.php',
                                'filepath' => 'hooks/filters',
                                'params'   => array()
                                );

$hook['post_controller'][] = array(
                                'class'    => '',
                                'function' => 'post_filter',
                                'filename' => 'init.php',
                                'filepath' => 'hooks/filters',
                                'params'   => array()
                                );

//
// This hook enables the layout pattern
//
$hook['display_override'][] = array('class'    => 'Yield',
                                    'function' => 'doYield',
                                    'filename' => 'yield.php',
                                    'filepath' => 'hooks'
                                   );
                                   
                                   


/* End of file hooks.php */
/* Location: ./system/application/config/hooks.php */