<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
|  Filters configuration
| -------------------------------------------------------------------
|
| Note: The filters will be applied in the order that they are defined
|
| Example configuration:
|
| $filter['auth'] = array('exclude', array('login/*', 'about/*'));
| $filter['cache'] = array('include', array('login/index', 'about/*', 'register/form,rules,privacy'));
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

$filter['doctrine_transaction'] = array('exclude', array());
?>