<?php

class Home extends My_Controller	{
	
	function Home()
	{		
		parent::Controller();	
	}
	
	function index()
	{		
		$this->load->view('home');
	}
}

?>