<?php
	include('admin_elements/admin_header.php');
	$module = 'email_campaigns';
	$module_caption = 'Email campaign';
	$tbl_name = $tbl_prefix.$module;
	$error_message = ''; $success_message = '';
	#########################################


	/**
	**************************************
			@@@ REPLICATE CAMPAIGN @@@
	**************************************
	**/
	if ( ($action=="replicate_$module" && !empty($id))){

		$result = $mysqli->query("SELECT * FROM `$tbl_name` WHERE id=$id");
		$row = $result->fetch_array();

			$list_id						= stripslashes($row['list_id']);
			$template_id				= stripslashes($row['template_id']);
			// $email_template			= stripslashes($row['email_template']);
			$campaign_name			= stripslashes($row['campaign_name']);
			$subject						= stripslashes($row['subject']);
			$from_email 			  = stripslashes($row['from_email']);
			$from_name 					= stripslashes($row['from_name']);

			$campa_name .= '- Copy';

			// REPLICATE EMAIL LIST
			$replicate_campaign = $mysqli->query("INSERT INTO `$tbl_name`(list_id, template_id, campaign_name, subject, from_email, from_name) VALUES ('".$list_id."', '".$template_id."', '".$campaign_name."', '".$subject."', '".$from_email."', '".$from_name."')");

		if($replicate_campaign){
			$success_message = "$module_caption Replicated Successfully.";
			$new_campaign_id = $mysqli->insert_id;
			fp__($tbl_name, $new_campaign_id);

					//IMPORT EMAIL TEMPLATE INTO CAMPAIGN TABLE
					$mysqli->query("INSERT INTO `$tbl_name`(email_template) SELECT email_template FROM `".$GLOBALS['TBL']['PREFIX']."email_templates` WHERE id=$template_id");

					// COPY ALL EMAIL LIST SUBSCRIBERS TO CAMPAIGN REPORTS
					$result_list = $mysqli->query("SELECT * FROM `".$GLOBALS['TBL']['PREFIX']."subscribers` WHERE list_id=$list_id");
					while ($row_list = $result_list->fetch_array()){
						$email 			= stripslashes($row_list['email']);
						$first_name = stripslashes($row_list['first_name']);
						$last_name 	= stripslashes($row_list['last_name']);

						$mysqli->query("INSERT INTO `".$GLOBALS['TBL']['PREFIX']."email_reports`(campaign_id, email, first_name, last_name) VALUES ('".$new_campaign_id."', '".$email."', '".$first_name."', '".$last_name."')");
					}

		} else {
			$error_message = "Sorry! $module Could Not Be Replicated.";
		}

	/**
	**************************************
				 	   @@@ START @@@
	**************************************
	**/
	} else if ( ($action=="start_$module" && !empty($id))){

		$result = $mysqli->query("UPDATE `$tbl_name` SET active=1 WHERE id=$id");
		if ($result)
			$success_message = "$module_caption Started Successfully.";
		else
			$error_message = "Sorry! $module Could Not Be Started.";

	/**
	**************************************
				 	   @@@ STOP @@@
	**************************************
	**/
	} else if ( ($action=="stop_$module" && !empty($id))){

		$result = $mysqli->query("UPDATE `$tbl_name` SET active=0 WHERE id=$id");
		if ($result)
			$success_message = "$module_caption Stoped Successfully.";
		else
			$error_message = "Sorry! $module Could Not Be Stoped.";

	/**
	**************************************
			 	   @@@ DELETE @@@
	**************************************
	**/
	} else if ( ($action=="delete_$module" && !empty($id))){

		//SUPERADMIN CAN DELETE ANY DATA
		if ($_SESSION[$project_pre]['type']=='superadmin'){
			$result = $mysqli->query("DELETE FROM `$tbl_name` WHERE id=$id");

			// CASCADE DELETE FROM CAMPAIGN REPORTS
			$result = $mysqli->query("DELETE FROM `".$GLOBALS['TBL']['PREFIX']."email_reports` WHERE campaign_id=$id");


		//ADMIN CAN DELETE ONLY HIS/HER DATA
		} else {
			$result = $mysqli->query("DELETE FROM `$tbl_name` WHERE id=$id AND created_by='".$_SESSION[$project_pre]['admin_id']."'");

			// CASCADE DELETE FROM CAMPAIGN REPORTS
			$result = $mysqli->query("DELETE FROM `".$GLOBALS['TBL']['PREFIX']."email_reports` WHERE campaign_id=$id");
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
					<a href="companies_active.php"><button class="btn btn-info">Add <?php echo $module_caption;?></button></a>
				</div>
			</div>
		</div>
	</div>
	<div class="content">

		<?php include('admin_elements/breadcrumb.php');?>

		<div class="row">
			<div class="col-md-12">

		        <!-- <div class="text-center"><a href="<?php echo $module?>.php"><button class="btn btn-info">Add <?php echo $module_caption;?></button></a></div> -->

		        <table id="grid-<?php echo $module;?>" class="table table-striped table-bordered table-hover">
		            <thead>
		            <tr>
									<th width="40">Manage</th>
									<th>Campaign</th>
		              <th>Subject</th>
		              <th>From Name</th>
		              <th>From Email</th>
		              <th>Reply To</th>
		              <th>Bounce To</th>
		              <th>Created</th>
		            </tr>
		          </thead>
		        </table>

			</div>
		</div>

		<?php include('admin_elements/copyright.php');?>
	</div>
</div>
<?php include('admin_elements/admin_footer.php');?>
