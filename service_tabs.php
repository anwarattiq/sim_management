<?php
	include('admin_elements/admin_header.php');
	$module = 'service_tabs';
	$module_caption = 'Service Tab';
	$tbl_name = $tbl_prefix . $module;

	$photo_upload_path = '../uploads/' . $module . '/';
	$allowed_file_size = $GLOBALS['PHOTO']['MAX_UPLOAD_SIZE']; //MB Bytes
	$thumb_width = '150';
	$thumb_height = '150';
	$image_width = '750';
	$image_height = '650';

	$error_message = '';
	$success_message = '';
	#########################################
	if (isset($_POST['publish'])) 							$publish = 1;
	else $publish = 0;

	/**
	 **************************************
	@@@ GET ALL VARIABLES ADD/UPDATE @@@
	**************************************
	**/
	if ($action == "update_$module" || $action == "add_$module") {
		$slug						= $mysqli->real_escape_string(stripslashes($_POST['slug']));
		$flaticon					= $mysqli->real_escape_string(stripslashes($_POST['flaticon']));
		$service_tab_name			= $mysqli->real_escape_string(stripslashes($_POST['service_tab_name']));
		$service_tab_description	= $mysqli->real_escape_string(stripslashes($_POST['service_tab_description']));
	} else {
		$slug						= '';
		$flaticon					= '';
		$service_tab_name			= '';
		$service_tab_description	= '';
	}

	/**
	 *************************
	@@@ DELETE PHOTO ONLY @@@
	*************************
	**/
	if (isset($_REQUEST['delete_photo']) && $_REQUEST['delete_photo'] == 1 && !empty($id)) {
		$photo =	get_photo_name('photo', $id, $tbl_name);;
		if (!empty($photo)) {
			delete_photo($photo, $photo_upload_path, '1'); 	// DELETE OLD THUMB
			delete_photo($photo, $photo_upload_path, '0');		// DELETE OLD PHOTO

			$result = $mysqli->query("UPDATE `$tbl_name` SET photo='' WHERE id=$id");
			$success_message = 'Image Deleted Successfully.';
		}
	}

	/**
	 **************
	@@@ UPDATE @@@
	**************
	**/
	if ($action == "update_$module" && !empty($id)) {

		if (empty($service_tab_name)) {
			$error_message = 'Service Tab name is mandatory.';
		} else if (empty($slug)) {
			$error_message = 'Slug is mandatory.';
		} else if (empty($flaticon)) {
			$error_message = 'Flaticon is mandatory.';
		} else if (empty($service_tab_description)) {
			$error_message = 'Service Tab description is mandatory.';
		} else {

			/////// UPLOAD PHOTO ////////
			$photo = $_FILES["photo"]["name"];

			if (!empty($photo)) {
				$old_photo 		= get_photo_name('photo', $id, $tbl_name);;
				$renamed 		= full_rename($photo, $slug);
				$message 		= upload_photo_with_thumb('photo', $renamed, $photo_upload_path, $allowed_file_size, $old_photo, $thumb_width, $thumb_height);
				if ($message)	$error_message = $message;
				else			$result = $mysqli->query("UPDATE `$tbl_name` SET photo='$renamed' WHERE id=$id");
			} //endif

			//////////////////////////////////////////////////
			$update_row = $mysqli->query("
								UPDATE `$tbl_name` SET
									slug					= '" . $slug . "',
									flaticon				= '" . $flaticon . "',
									service_tab_name		= '" . $service_tab_name . "',
									service_tab_description	= '" . $service_tab_description . "',
									publish 				= '" . $publish . "'
								WHERE id=$id");

			if ($update_row) {
				$success_message = "$module_caption Updated Successfully.";
				fp__($tbl_name, $id);
				header("Location:listing_$module.php?success_message=$success_message");
			} else {
				$error_message = "Sorry ! $module_caption Could Not Be Updated.";
				header("Location:$module.php?action=edit_$module&id=$id&error_message=$error_message");
			}
		}

		/**
		 ***********
		@@@ ADD @@@
		***********
		**/
	} else if ($action == "add_$module") {

		if (empty($service_tab_name)) {
			$error_message = 'Service Tab name is mandatory.';
		} else if (checkDuplicateRow($tbl_name, 'service_tab_name', $service_tab_name)) {
			$error_message = 'Duplicate Service Tab Name. Please enter different.';
		} else if (empty($slug)) {
			$error_message = 'Slug is mandatory.';
		} else if (empty($flaticon)) {
			$error_message = 'Flaticon is mandatory.';
		} else if (empty($service_tab_description)) {
			$error_message = 'Service Tab description is mandatory.';
		} else {

			$insert_row = $mysqli->query("INSERT INTO `$tbl_name`(slug, flaticon, service_tab_name, service_tab_description, publish) VALUES ('" . $slug . "', '" . $flaticon . "', '" . $service_tab_name . "',  '" . $service_tab_description . "', '" . $publish . "'); ");

			if ($insert_row) {
				$id = $mysqli->insert_id;
				$success_message = "$module_caption Saved Successfully.";
				fp__($tbl_name, $id);
				/////// UPLOAD PHOTO ////////
				$photo = $_FILES["photo"]["name"];
				if (!empty($photo)) {
					$renamed 		= full_rename($photo, $slug);
					$message 		= upload_photo_with_thumb('photo', $renamed, $photo_upload_path, $allowed_file_size, $old_photo, $thumb_width, $thumb_height);
					if ($message)	$error_message = $message;
					else			$result = $mysqli->query("UPDATE `$tbl_name` SET photo='$renamed' WHERE id=$id");
				} //endif
				header("Location:listing_$module.php?success_message=$success_message");
				//////////////////////////////////////////////////
			} else {
				$error_message = "Sorry ! $module_caption could not Save.";
				header("Location:$module.php?error_message=$error_message");
			}
		}
	}


	/**
	 ************
		@@@ EDIT @@@
	************
	**/

	if (!empty($id)) {

		$result = $mysqli->query("SELECT * FROM `$tbl_name` WHERE id=$id");
		$row = $result->fetch_array();

		$slug						= stripslashes($row['slug']);
		$flaticon					= stripslashes($row['flaticon']);
		$service_tab_name			= stripslashes($row['service_tab_name']);
		$service_tab_description	= stripslashes($row['service_tab_description']);
		$publish 					= stripslashes($row['publish']);
	}

	$photo =	get_photo_name('photo', $id, $tbl_name);
	///////////////////////////////////////////////////////////////////////////////
?>
<div class="content-wrapper">
	<div class="content">

		<?php include('admin_elements/breadcrumb.php'); ?>

		<form method="post" id="frm<?php echo $module; ?>" name="frm<?php echo $module; ?>" action="<?php echo $module; ?>.php" enctype="multipart/form-data" class="form-horizontal">
			<?php if (($action == "edit_$module" || $action == "update_$module") && !empty($id)) { ?>
				<input type="hidden" name="action" id="action" value="update_<?php echo $module; ?>" />
				<input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
			<?php } else { ?>
				<input type="hidden" name="action" id="action" value="add_<?php echo $module; ?>" />
			<?php } ?>

			<div class="row">

				<div class="col-md-6">
					<div class="panel panel-flat">
						<div class="panel-heading">

							<div class="row">
								<div class="col-md-3">
									<div class="col-md-12">
										<h5><?php echo $module_caption; ?> Details </h5>
									</div>
								</div>

								<div class="col-md-9 text-right">
									<div class="col-md-4"></div>
									<div class="col-md-4">
										<input type="checkbox" name="publish" id="publish" data-on-color="success" data-size="small" <?php if ($publish == '1') { ?>checked="checked" <?php } ?> />
									</div>
									<div class="col-md-2">
										<a href="listing_<?php echo $module; ?>.php"><button type="button" class="btn btn-default"> Listing </button></a>
									</div>
									<div class="col-md-2">
										<button type="submit" class="btn btn-info"><?php if (($action == "edit_$module" || $action == "update_$module") && !empty($id)) { ?>Update<?php } else { ?>Save<?php } ?> </button>
									</div>
								</div>

							</div>

							<div class="panel-body">

								<div class="form-group">
									<label class="col-lg-3 control-label"><strong>Service Tab Name</strong> <span class="f_req">*</span></label>
									<div class="col-lg-9">
										<input name="service_tab_name" id="service_tab_name" value="<?php echo $service_tab_name; ?>" class="form-control" type="text">
									</div>
								</div>

								<div class="form-group">
									<label class="col-lg-3 control-label"><strong>Slug</strong></label>
									<div class="col-lg-9">
										<input name="slug" id="slug" value="<?php echo $slug; ?>" class="form-control" type="text">
										<span class="help-block">Tab Anchor (type manually)</span>
									</div>
								</div>

								<div class="form-group">
									<label class="col-lg-3 control-label"><strong>Flaticon</strong></label>
									<div class="col-lg-9">
										<input name="flaticon" id="flaticon" value="<?php echo $flaticon; ?>" class="form-control" type="text">
										<span class="help-block">e.g. flaticon-digital-marketing</span>
									</div>
								</div>

								<div class="form-group">
									<label class="col-lg-3 control-label"><strong>Photo</strong><br>[<?php echo $image_width; ?>px x <?php echo $image_height; ?>px]</label>
									<div class="col-lg-9">
										<input type="file" name="photo" id="photo" class="file-styled" width="150">
										<span class="help-block">Accepted formats: gif, png, jpg. Max file size 5Mb</span>
									</div>
								</div>

								<div class="form-group">
									<label class="col-lg-3 control-label">&nbsp;</label>
									<div class="col-lg-9">
										<?php if (!empty($photo) && $photo != 'noimage.png') { ?>
											<div class="form-group">
												<a href="<?php echo $photo_upload_path . $photo ?>" target="_blank">
													<img src="<?php echo $photo_upload_path . $photo ?>" alt="" width="<?php echo $thumb_width;?>" />
												</a>
												<p><a href="<?php echo $module; ?>.php?action=<?php echo $action; ?>&id=<?php echo $id; ?>&delete_photo=1';">
														<button type="button" class="btn btn-default btn-sm" name="delete_photo" id="delete_photo">Delete Photo</button></a>
												</p>
											</div>
										<?php } ?>
									</div>
								</div>

							</div>

							<!-- <div class="text-left">
						<button type="submit" class="btn btn-info"><?php if (($action == "edit_$module" || $action == "update_$module") && !empty($id)) { ?>Update<?php } else { ?>Save<?php } ?> <?php echo $module_caption; ?></button>
						<a href="listing_<?php echo $module; ?>.php"><button type="button" class="btn btn-default"> Back to <?php echo ucfirst($module_caption); ?> Listing </button></a>
					</div> -->

						</div>
					</div>

				</div>

				<div class="col-md-6">
					<div class="panel panel-flat">
						<div class="panel-heading">
							<div class="panel-body">

								<div class="form-group">
									<label><strong>Service Tab description</strong> <span class="f_req">*</span></label>
									<textarea class="sepH_a form-control" rows="3" cols="1" id="service_tab_description" name="service_tab_description"><?php echo $service_tab_description; ?></textarea>
								</div>

							</div>
						</div>
					</div>
				</div>

			</div>
		</form>

		<?php include('admin_elements/copyright.php'); ?>
	</div>
</div>
<?php include('admin_elements/admin_footer.php'); ?>