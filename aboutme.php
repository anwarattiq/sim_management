<?php
include('admin_elements/admin_header.php');
$module = 'aboutme';
$module_caption = 'About Me';
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

/**
 ************************************
	@@@ GET ALL VARIABLES ADD/UPDATE @@@
 ************************************
 **/
if ($action == "update_$module") {

	$heading			= $mysqli->real_escape_string(stripslashes($_POST['heading']));
	$subheading			= $mysqli->real_escape_string(stripslashes($_POST['subheading']));
	$summary			= $mysqli->real_escape_string(stripslashes($_POST['summary']));
	$heading2			= $mysqli->real_escape_string(stripslashes($_POST['heading2']));
	$subheading2			= $mysqli->real_escape_string(stripslashes($_POST['subheading2']));
	$description		= $mysqli->real_escape_string(stripslashes($_POST['description']));
} else {
	$heading			= '';
	$subheading			= '';
	$summary			= '';
	$heading2			= '';
	$subheading2		= '';
	$description		= '';
}


/**
 ***********************
	@@@ DELETE PHOTO @@@
 ***********************
 **/
if (isset($_REQUEST['delete_photo']) && $_REQUEST['delete_photo'] == 1) {

	$photo = get_photo_name('photo', 1, $tbl_name);

	if (!empty($photo)) {
		delete_photo_and_thumb($photo, $photo_upload_path);

		$rs = $mysqli->query("UPDATE `$tbl_name` SET photo='' WHERE id=1");
	}
}


/**
 **************
	@@@ UPDATE @@@
 **************
 **/
if ($action == "update_$module") {

	if (empty($heading)) {
		$error_message = 'Heading is mandatory.';
	} else if (empty($subheading)) {
		$error_message = 'Subheading is mandatory.';
	} else if (empty($summary)) {
		$error_message = 'Summary is mandatory.';
	} else if (empty($heading2)) {
		$error_message = 'Heading2 is mandatory.';
	} else if (empty($subheading2)) {
		$error_message = 'Subheading2 is mandatory.';
	} else if (empty($description)) {
		$error_message = 'Description is mandatory.';
	} else {
		/////// UPLOAD PHOTO ////////
		$photo = $_FILES["photo"]["name"];
		if (!empty($photo)) {
			$old_photo 				= get_photo_name('photo', $id, $tbl_name);
			$renamed 				= full_rename($photo, 'imranonline-photo');
			$message 				= upload_photo_without_thumb('photo', $renamed, $photo_upload_path, $allowed_file_size);
			if ($message) 			$error_message = $message;
			else 					$result = $mysqli->query("UPDATE `$tbl_name` SET photo='$renamed' WHERE id=1");
		}
		//////////////////////////////////////////////////

		//Update Query
		$update_row = $mysqli->query("
			UPDATE `$tbl_name` SET
				heading					= '" . $heading . "',
				subheading				= '" . $subheading . "',
				summary					= '" . $summary . "',
				heading2				= '" . $heading2 . "',
				subheading2				= '" . $subheading2 . "',
				description				= '" . $description . "'

			WHERE id=1");
		if ($update_row) {
			$success_message = "$module_caption Updated Successfully.";
			fp__($tbl_name, 1);
			// header("Location:homepage.php.php?success_message=$success_message");
		} else {
			$error_message = "Sorry ! $module_caption Could Not Be Updated.";
			//header("Location:$module.php?action=edit_$module&id=$id&error_message=$error_message");
		}
	}
}

/**
 ************
	@@@ EDIT @@@
 ************
 **/

$result_homepage 	= $mysqli->query("SELECT * FROM `$tbl_name` WHERE id=1");
$row_homepage		= $result_homepage->fetch_array();

$heading					= stripslashes($row_homepage['heading']);
$subheading					= stripslashes($row_homepage['subheading']);
$summary					= stripslashes($row_homepage['summary']);
$heading2					= stripslashes($row_homepage['heading2']);
$subheading2				= stripslashes($row_homepage['subheading2']);
$description				= stripslashes($row_homepage['description']);
$photo						= stripslashes($row_homepage['photo']);

$photo 						= get_photo_name('photo', 1, $tbl_name);
/////////////////////////////////////////////////////////////////////
?>

<div class="content-wrapper">

	<?php include('admin_elements/breadcrumb.php'); ?>

	<!-- Content area -->
	<div class="content">

		<!-- Dashboard content -->
		<div class="row">
			<div class="col-lg-12">

				<!-- Quick stats boxes -->
				<form method="post" id="frm<?php echo $module; ?>" name="frm<?php echo $module; ?>" action="<?php echo $module; ?>.php" enctype="multipart/form-data" class="form-horizontal">
					<input type="hidden" name="action" id="action" value="update_<?php echo $module; ?>" />

					<div class="row">

						<div class="col-md-6">
							<div class="panel panel-flat">
								<div class="panel-heading">

									<div class="row">
										<div class="col-md-10">
											<div class="col-md-12">
												<h5><?php echo $module_caption; ?> </h5>
											</div>
										</div>

										<div class="col-md-2 text-right">
											<div class="text-left">
												<button type="submit" class="btn btn-info">Update</button>
											</div>
										</div>
									</div>

								</div>

								<div class="panel-body">

									<div class="form-group">
										<label class="col-lg-3 control-label"><strong>Heading</strong> <span class="f_req">*</span></label>
										<div class="col-lg-9">
											<input name="heading" id="heading" value="<?php echo $heading; ?>" class="form-control" type="text">
										</div>
									</div>

									<div class="form-group">
										<label class="col-lg-3 control-label"><strong>Subheading</strong> <span class="f_req">*</span></label>
										<div class="col-lg-9">
											<input name="subheading" id="subheading" value="<?php echo $subheading; ?>" class="form-control" type="text">
										</div>
									</div>

									<div class="form-group">
										<label class="col-lg-3 control-label"><strong>Summary</strong> <span class="f_req">*</span></label>
										<div class="col-lg-9">
											<textarea class="sepH_a form-control" id="summary" rows="10" name="summary"><?php echo $summary; ?></textarea>
										</div>
									</div>

									<div class="form-group">
										<label class="col-lg-3 control-label"><strong>Photo</strong> <br />[<?php echo $image_width; ?>px x <?php echo $image_height; ?>px]</label>
										<div class="col-lg-9">
											<input type="file" name="photo" id="photo" class="file-styled">
										</div>
									</div>

									<div class="form-group">
										<label class="col-lg-3 control-label">&nbsp;</label>
										<div class="col-lg-9">
											<?php if (!empty($photo)) { ?>
												<div class="form-group">
													<a href="<?php echo $photo_upload_path . $photo; ?>" target="_blank">
														<img src="<?php echo $photo_upload_path . $photo ?>" alt="" width="<?php echo $thumb_width; ?>" />
													</a>
													<p><a href="<?php echo $module; ?>.php?delete_photo=1">
															<button type="button" class="btn btn-default btn-sm" name="delete_photo" id="delete_photo">Delete</button></a>
													</p>
												</div>
											<?php } ?>
										</div>
									</div>

									<div class="form-group">
										<label class="col-lg-3 control-label"><strong>Heading2</strong> <span class="f_req">*</span></label>
										<div class="col-lg-9">
											<input name="heading2" id="heading2" value="<?php echo $heading2; ?>" class="form-control" type="text">
										</div>
									</div>

									<div class="form-group">
										<label class="col-lg-3 control-label"><strong>Subheading2</strong> <span class="f_req">*</span></label>
										<div class="col-lg-9">
											<input name="subheading2" id="subheading2" value="<?php echo $subheading2; ?>" class="form-control" type="text">
										</div>
									</div>

								</div>
							</div>

						</div>

						<div class="col-md-6">
							<div class="panel panel-flat">
								<div class="panel-body">

									<div class="form-group">
										<div class="col-lg-12">
											<textarea class="sepH_a form-control" id="description" name="description"><?php echo $description; ?></textarea>
										</div>
									</div>

								</div>

							</div>
						</div>

					</div>
				</form>

			</div>

		</div>
	</div>
	<!-- /dashboard content -->

	<?php include('admin_elements/copyright.php'); ?>

</div>
<!-- /content area -->

</div>

<?php include('admin_elements/admin_footer.php'); ?>