<?php

	require_once ('../config/globals.php');
	require_once ('../config/database.php');

	$data_ids = $_POST['data_ids'];
	$data_id_array = explode(",", $data_ids);
	if(!empty($data_id_array)) {
			foreach($data_id_array as $id) {
					$mysqli->query("DELETE FROM `".$GLOBALS['TBL']['PREFIX']."inquiries` WHERE id=$id");
			}
	}


// src:https://coderexample.com/datatable-bulk-delete-server-side/
