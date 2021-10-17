<?php
	include('admin_elements/admin_header.php');
	$module = 'email_templates';
	$module_caption = 'Email Template';
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

			$template_name 	= stripslashes($row['template_name']);
			$email_template	= $row['email_template'];
			$photo					= stripslashes($row['photo']);

			$template_name .= ' - Copy';

			// REPLICATE EMAIL LIST
			$email_template = $mysqli->real_escape_string(stripslashes($row['email_template']));
			$replicate_template = $mysqli->query("INSERT INTO `$tbl_name`(template_name, email_template, photo) VALUES ('".$template_name."', '".$email_template."', '".$photo."')");

				if($replicate_template){
					$id = $mysqli->insert_id;
					fp__($tbl_name, $id);
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

			$photo = getPhotoName($id, $tbl_name);
			$result = $mysqli->query("DELETE FROM `$tbl_name` WHERE id=$id");

			if (!empty($photo)){
				delete_photo($photo, $photo_upload_path, '1'); 	// DELETE OLD THUMB
				delete_photo($photo, $photo_upload_path, '0');		// DELETE OLD PHOTO
			}

		//ADMIN CAN DELETE ONLY HIS/HER DATA
		} else {

			$photo = getPhotoName($id, $tbl_name);
			$result = $mysqli->query("DELETE FROM `$tbl_name` WHERE id=$id AND created_by='".$_SESSION[$project_pre]['admin_id']."'");

			if (!empty($photo)){
				delete_photo($photo, $photo_upload_path, '1'); 	// DELETE OLD THUMB
				delete_photo($photo, $photo_upload_path, '0');		// DELETE OLD PHOTO
			}
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
				<h4><i class="icon-circle-right2 position-left"></i> Listing <?php echo '<strong>'.ucwords($type).'</strong>';?> <?php echo $module_caption;?></h4>
				<a class="heading-elements-toggle"><i class="icon-more"></i></a>
			</div>
			<div class="heading-elements">
				<div class="heading-btn-group">
					<a href="<?php echo $module;?>.php"><button class="btn btn-info">Add <?php echo $module_caption;?></button></a>
				</div>
			</div>
		</div>
	</div>
	<div class="content">

		<?php include('admin_elements/breadcrumb.php');?>

		<div class="row">
			<div class="col-md-12">

		        <!-- <div class="text-center"><a href="<?php echo $module?>.php"><button class="btn btn-info">Add <?php echo $module_caption;?></button></a></div> -->

		        <table id="grid-<?php echo $module;?>" class="display responsive no-wrap table table-striped table-bordered table-hover">
		            <thead>
		            <tr>
									<th width="40">Manage</th>
									<th></th>
									<th width="160">Image</th>
									<th>Template Name</th>
									<th></th>
									<th></th>
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
