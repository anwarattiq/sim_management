<?php
	//print_r($_REQUEST);
	if (isset($_REQUEST['action']) && !empty($_REQUEST['action'])) 	{ $action = $_REQUEST['action']; } 	else { $action = ''; }
	if (isset($_REQUEST['id']) && !empty($_REQUEST['id']))			{ $id = $_REQUEST['id']; } 			else { $id = ''; }
	if (isset($_REQUEST['parent']) && !empty($_REQUEST['parent']))	{ $parent = $_REQUEST['parent']; } 	else { $parent = ''; }
	// if (isset($_REQUEST['category_id']) && !empty($_REQUEST['category_id']))		{ $category_id = $_REQUEST['category_id']; } else { $category_id = ''; }
	//if (isset($_REQUEST['page']) && !empty($_REQUEST['page']) )					{ $page = $_REQUEST['page']; } else { $page = '1'; }
	//if (isset($_REQUEST['site']) && !empty($_REQUEST['site']))					{ $site = $_REQUEST['site']; } else { $site = ''; }
