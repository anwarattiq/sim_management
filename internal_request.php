<?php
	include_once('config/globals.php');
	include_once('config/database.php');

	if (isset($_REQUEST['ajax_action'])) {
		$ajax_action = $mysqli->real_escape_string(stripslashes($_REQUEST['ajax_action']));

	} else {
		$ajax_action = '';
	}
	///////////////////////////////////////////////////////////////////////////////////////
	switch ($ajax_action) {

    /**
		***********************************
		@ GET EMAIL LIST
		***********************************
		**/
		case 'email_list':

			$list_id 	= '';
			if (isset($_POST['list_id']))				{ $list_id 	= $mysqli->real_escape_string(stripslashes($_POST['list_id'])); 	}

			$status = '';
			if (!empty($list_id)){
				$result = $mysqli->query("SELECT * FROM `".$GLOBALS['TBL']['PREFIX']."email_lists` WHERE id=$list_id");
				$row 		= $result->fetch_array();
				$from_name			= stripslashes($row['from_name']);
				$from_email			= stripslashes($row['from_email']);

				$status  = $from_name.'@@@@@'.$from_email;
			}

			print($status);
		break;


    /**
		***********************************
		@ GET EMAIL TEMPLATE
		***********************************
		**/
		case 'email_template':

			$template_id 	= '';
			if (isset($_POST['template_id']))				{ $template_id 	= $mysqli->real_escape_string(stripslashes($_POST['template_id'])); 	}

			$status = '';
			if (!empty($template_id)){
				$result = $mysqli->query("SELECT * FROM `".$GLOBALS['TBL']['PREFIX']."email_templates` WHERE id=$template_id");
				$row 		= $result->fetch_array();
					$email_template			= stripslashes($row['email_template']);

					$status  = $email_template;
			}

			print($status);
		break;


	}//switch
