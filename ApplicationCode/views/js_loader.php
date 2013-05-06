
<!-- Including Ext Js and Custom Ext Components -->
<?php 
if(config_item("site_mode") == "production")
{
?>
<script src="<?php print url_to("Assets/javascript", "ext-3.2.0/adapter/ext/ext-base.js") ?>"  type="text/javascript"></script>
<script src="<?php print url_to("Assets/javascript", "ext-3.2.0/ext-all.js") ?>"  type="text/javascript"></script>
<?php 
} 
else
{
?>
<script src="<?php print url_to("Assets/javascript", "ext-3.2.0/adapter/ext/ext-base-debug.js") ?>"  type="text/javascript"></script>
<script src="<?php print url_to("Assets/javascript", "ext-3.2.0/ext-all-debug.js") ?>"  type="text/javascript"></script>
<?php 
}
?>
<script type="text/javascript">
//<![CDATA[
	Ext.QuickTips.init();
	Ext.Ajax.disableCaching = false;           
//]]>
</script>
<link rel="stylesheet" type="text/css" href="<?php print url_to("Assets/javascript", "ext-3.2.0/resources/css/ext-all.css") ?>" />
<!-- END Including Ext Js -->