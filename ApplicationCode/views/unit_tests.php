
<h2>Executing Method Assertions: <i style="color:#FF6600"><?php echo $method_name ?></i></h2>
<i style="color:gray"><?php echo $test_description ?></i>
<ol>
<?php foreach($test_results as $result) {?>
<li>

<div class='UnitTest'>
 <span class='TestResult <?php echo $result["Result"]?>'><?php echo $result["Result"] ?></span>
 <span class='TestName'><?php echo $result["Test Name"] ?></span>
 <span class='TestLineNumber'>Line #<?php echo $result["Line Number"]?></span> 
</div>

</li>
<?php }

if(isset($error_message) == true)
{
	echo "<div class='error'>".$error_message."</div>";
}

?>

</ol>
<br/><br/>