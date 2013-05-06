<?php $this->load->helper("common"); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html dir="ltr" xmlns="http://www.w3.org/1999/xhtml">

	<head profile="http://gmpg.org/xfn/11">
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
        
        <title>My Pages</title>       
        <link rel=stylesheet href='<?php print url_to("Assets/css","default.css") ?>' />
        <link rel=stylesheet href='<?php print url_to("Assets/css","unit_tests.css") ?>' />
	</head>

<body>
<div id="ContentPanel">
	<h1>My Pages &gt; Unit Testing &gt; <?php echo get_class(get_instance()) ?></h1>
	
	<div id="Menu">
		<b>Site Diagnostics:</b>
		<?php echo link_to("Ensure Config and Environment is in a valid state", "unit_tests/ProjectConfig")?>
		<br/><br/>
	
		<b>DB Setup Helpers:</b>
		<?php echo link_to("Setup with baseline data", "unit_tests/ResetEnvironment")?> | 
		<?php echo link_to("Setup with Test Data", "unit_tests/InsertTestingData")?>
		<br/><br/>
	
		<b>All Tests: | </b>
		<?php 		
		
		$unit_test_controllers = dir_list(APPPATH.DIRECTORY_SEPARATOR.'controllers'.DIRECTORY_SEPARATOR.'unit_tests');
		sort($unit_test_controllers);
		foreach($unit_test_controllers as $controller)
		{
			// strip the extension			
			$controller = str_replace(EXT, "", $controller);
			echo link_to($controller, 'unit_tests/'.$controller)." | ";
		}
		?>
	</div>
	
	<div id="Tests">
	{yield}
	</div>
</div>
</body>
</html>