<?php
$website_name = "rentmn.gr";
function assignWebsiteName(&$tpl){
	global $website_name;
	$tpl->assign("website_name", $website_name);
	$tpl->assign("website_name_first_caps", ucfirst($website_name));
}
?>