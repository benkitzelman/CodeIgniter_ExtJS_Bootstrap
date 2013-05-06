<?php 
$this->load->helper("common"); 
$this->load->helper("web"); 
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html dir="ltr" xmlns="http://www.w3.org/1999/xhtml">

	<head profile="http://gmpg.org/xfn/11">
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
        <title>My Pages</title>
        <?php $this->load->view("js_loader"); ?>
        <link rel=stylesheet href='<?php print url_to("Assets/css","default.css") ?>' />        
	</head>

	<body>
		
	
	
	<h1>{page_title}</h1>

	
	<div class="ContentPanel">			
	{yield}		
	</div>		
	
	
	
	</body>

</html>