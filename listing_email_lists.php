<?php
	include('admin_elements/admin_header.php');
	$module = 'email_lists';
	$module_caption = 'Email Lists';
	$tbl_name = $tbl_prefix.$module;
	$error_message = ''; $success_message = '';
	#########################################

	/**
	**************************************
			@@@ REPLICATE EMAIL LIST @@@
	**************************************
	**/
	if ( ($action=="replicate_$module" && !empty($id))){

		$result = $mysqli->query("SELECT * FROM `$tbl_name` WHERE id=$id");
		$row = $result->fetch_array();

			$list_name					= stripslashes($row['list_name']);
			$from_email 			  = stripslashes($row['from_email']);
			$from_name 					= stripslashes($row['from_name']);
			$company 						= stripslashes($row['company']);
			$address1 					= stripslashes($row['address1']);
			$address2 					= stripslashes($row['address2']);
			$city 							= stripslashes($row['city']);
			$zip_postcode 			= stripslashes($row['zip_postcode']);
			$country 						= stripslashes($row['country']);
			$phone 							= stripslashes($row['phone']);

			$list_name .= '- Copy';

			// REPLICATE EMAIL LIST
			$replicate_email_list = $mysqli->query("INSERT INTO `$tbl_name`(list_name, from_email, from_name, company, address1, address2, city, zip_postcode, country, phone) VALUES ('".$list_name."', '".$from_email."', '".$from_name."', '".$company."', '".$address1."', '".$address2."', '".$city."', '".$zip_postcode."', '".$country."', '".$phone."')");

		if($replicate_email_list){
			$new_list_id = $mysqli->insert_id;
			fp__($tbl_name, $new_list_id);


					// REPLICATE SUBSCRIBERS
					$result_subscribers = $mysqli->query("SELECT * FROM `".$GLOBALS['TBL']['PREFIX']."subscribers` WHERE list_id=$id");
					while ($row_subscribers 		= $result_subscribers->fetch_array()){
						$email						= stripslashes($row_subscribers['email']);
						$first_name				= stripslashes($row_subscribers['first_name']);
						$last_name 				= stripslashes($row_subscribers['last_name']);
						$status 					= stripslashes($row_subscribers['status']);

						$mysqli->query("INSERT INTO `".$GLOBALS['TBL']['PREFIX']."subscribers`(list_id, email, first_name, last_name, status) VALUES ('".$new_list_id."', '".$email."', '".$first_name."', '".$last_name."', '".$status."')");
					}//while

		} else {
			$error_message = "Sorry! $module Could Not Be Replicated.";
		}


	/**
	**************************************
				 	   @@@ PUBLISH @@@
	**************************************
	**/
	} else if ( ($action=="publish_$module" && !empty($id))){

		if ( publish($module_caption, $tbl_name, $id) )
			$success_message = "$module_caption Published Successfully.";
		else
			$error_message = "Sorry! $module Could Not Be Published.";

	/**
	**************************************
				 	   @@@ PUBLISH @@@
	**************************************
	**/
	} else if ( ($action=="unpublish_$module" && !empty($id))){

		if ( unpublish($module_caption, $tbl_name, $id) )
			$success_message = "$module_caption Un-Published Successfully.";
		else
			$error_message = "Sorry! $module Could Not Be Un-Published.";


	/**
	**************************************
			 	   @@@ DELETE @@@
	**************************************
	**/
	} else if ( ($action=="delete_$module" && !empty($id))){

		//SUPERADMIN CAN DELETE ANY DATA
		if ($_SESSION[$project_pre]['type']=='superadmin'){
			$result = $mysqli->query("DELETE FROM `$tbl_name` WHERE id=$id");

			// DELETE SUBSCRIBERS AS WELL
			$result = $mysqli->query("DELETE FROM `".$GLOBALS['TBL']['PREFIX']."subscribers` WHERE list_id=$id");

		//ADMIN CAN DELETE ONLY HIS/HER DATA
		} else {
			$result = $mysqli->query("DELETE FROM `$tbl_name` WHERE id=$id AND created_by='".$_SESSION[$project_pre]['admin_id']."'");

			// DELETE SUBSCRIBERS AS WELL
			$result = $mysqli->query("DELETE FROM `".$GLOBALS['TBL']['PREFIX']."subscribers` WHERE list_id=$id");
		}

		if($result){
			$success_message = "$module_caption Deleted Successfully.";
			header("Location:listing_$module.php?page=$page&success_message=$success_message");
		} else {
			$error_message = "Sorry! $module Could Not Be Deleted.";
		}
	}
	///////////////////////////////////////////////////////////////////////////////
?>
<div class="content-wrapper">
	<div class="page-header page-header-default">
		<div class="page-header-content">
			<div class="page-title">
				<h4><i class="icon-circle-right2 position-left"></i> Listing <?php echo $module_caption;?></h4>
				<a class="heading-elements-toggle"><i class="icon-more"></i></a>
			</div>
			<div class="heading-elements">
				<div class="heading-btn-group">
					<a href="<?php echo $module?>.php"><button class="btn btn-info">Add <?php echo $module_caption;?></button></a>
				</div>
			</div>
		</div>
	</div>
	<div class="content">

		<?php include('admin_elements/breadcrumb.php');?>

		<div class="row">
			<div class="col-md-12">

		        <!-- <div class="text-center"><a href="<?php echo $module?>.php"><button class="btn btn-info">Add <?php echo $module_caption;?></button></a></div> -->

		        <table id="grid-<?php echo $module;?>" class="table table-hover"> <!-- table-striped table-bordered table-hover-->
		            <thead>
		            <tr>
									<th>List Name</th>
									<th>Subscribers</th>
									<th>Manage</th>
									<th>Country</th>
									<th>Owner</th>
		            </tr>
		          </thead>
		        </table>

			</div>
		</div>

		<?php include('admin_elements/copyright.php');?>
	</div>
</div>
<?php include('admin_elements/admin_footer.php');?>
