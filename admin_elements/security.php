<?php
	if ( !isset($_SESSION[$project_pre]['admin_id']) || $_SESSION[$project_pre]['admin_id']=='' || $_SESSION[$project_pre]['admin_id']=='0') { header("Location:logout.php"); }


	$ip_address 					= $_SERVER['REMOTE_ADDR'];
	$activity 						= "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$activity_created_by 	= $_SESSION[$project_pre]['admin_id'];
	$activity_created 		= date("Y-m-d H:i:s");
	// $mysqli->query("INSERT INTO `" . $GLOBALS['TBL']['PREFIX'] . "admin_activities` (ip_address, activity, created_by, created) VALUES ('".$ip_address."', '".$activity."', '".$activity_created_by."', '".$activity_created."')");
