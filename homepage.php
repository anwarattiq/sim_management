<?php
include('admin_elements/admin_header.php');
$module = 'homepage';
$module_caption = 'Homepage';
$tbl_name = $tbl_prefix . $module;
$photo_upload_path = '../uploads/' . $module . '/';
$allowed_file_size = $GLOBALS['PHOTO']['MAX_UPLOAD_SIZE']; //MB Bytes
$error_message = '';
$success_message = '';
#########################################

/**
 ************************************
 @@@ GET ALL VARIABLES ADD/UPDATE @@@
 ************************************
 **/
if ($action == "update_$module") {

	$me_heading									= $mysqli->real_escape_string(stripslashes($_POST['me_heading']));
	$me_subheading								= $mysqli->real_escape_string(stripslashes($_POST['me_subheading']));
	$me_summary									= $mysqli->real_escape_string(stripslashes($_POST['me_summary']));

	$block1_service1_title						= $mysqli->real_escape_string(stripslashes($_POST['block1_service1_title']));
	$block1_service1_url						= $mysqli->real_escape_string(stripslashes($_POST['block1_service1_url']));
	$block1_service1_summary					= $mysqli->real_escape_string(stripslashes($_POST['block1_service1_summary']));
	$block1_service2_title						= $mysqli->real_escape_string(stripslashes($_POST['block1_service2_title']));
	$block1_service2_url						= $mysqli->real_escape_string(stripslashes($_POST['block1_service2_url']));
	$block1_service2_summary					= $mysqli->real_escape_string(stripslashes($_POST['block1_service2_summary']));
	$block1_service3_title						= $mysqli->real_escape_string(stripslashes($_POST['block1_service3_title']));
	$block1_service3_url						= $mysqli->real_escape_string(stripslashes($_POST['block1_service3_url']));
	$block1_service3_summary					= $mysqli->real_escape_string(stripslashes($_POST['block1_service3_summary']));
	$block1_service4_title						= $mysqli->real_escape_string(stripslashes($_POST['block1_service4_title']));
	$block1_service4_url						= $mysqli->real_escape_string(stripslashes($_POST['block1_service4_url']));
	$block1_service4_summary					= $mysqli->real_escape_string(stripslashes($_POST['block1_service4_summary']));

	$block2_heading								= $mysqli->real_escape_string(stripslashes($_POST['block2_heading']));
	$block2_subheading							= $mysqli->real_escape_string(stripslashes($_POST['block2_subheading']));
	$block2_service1_title						= $mysqli->real_escape_string(stripslashes($_POST['block2_service1_title']));
	$block2_service1_url						= $mysqli->real_escape_string(stripslashes($_POST['block2_service1_url']));
	$block2_service1_summary					= $mysqli->real_escape_string(stripslashes($_POST['block2_service1_summary']));
	$block2_service2_title						= $mysqli->real_escape_string(stripslashes($_POST['block2_service2_title']));
	$block2_service2_url						= $mysqli->real_escape_string(stripslashes($_POST['block2_service2_url']));
	$block2_service2_summary					= $mysqli->real_escape_string(stripslashes($_POST['block2_service2_summary']));
	$block2_service3_title						= $mysqli->real_escape_string(stripslashes($_POST['block2_service3_title']));
	$block2_service3_url						= $mysqli->real_escape_string(stripslashes($_POST['block2_service3_url']));
	$block2_service3_summary					= $mysqli->real_escape_string(stripslashes($_POST['block2_service3_summary']));
	$block2_service4_title						= $mysqli->real_escape_string(stripslashes($_POST['block2_service4_title']));
	$block2_service4_url						= $mysqli->real_escape_string(stripslashes($_POST['block2_service4_url']));
	$block2_service4_summary					= $mysqli->real_escape_string(stripslashes($_POST['block2_service4_summary']));

	$block3_heading								= $mysqli->real_escape_string(stripslashes($_POST['block3_heading']));
	$block3_subheading							= $mysqli->real_escape_string(stripslashes($_POST['block3_subheading']));
} else {
	$me_heading									= '';
	$me_subheading								= '';
	$me_summary									= '';

	$block1_service1_title						= '';
	$block1_service1_url						= '';
	$block1_service1_summary					= '';
	$block1_service2_title						= '';
	$block1_service2_url						= '';
	$block1_service2_summary					= '';
	$block1_service3_title						= '';
	$block1_service3_url						= '';
	$block1_service3_summary					= '';
	$block1_service4_title						= '';
	$block1_service4_url						= '';
	$block1_service4_summary					= '';

	$block2_heading								= '';
	$block2_subheading							= '';
	$block2_service1_title						= '';
	$block2_service1_url						= '';
	$block2_service1_summary					= '';
	$block2_service2_title						= '';
	$block2_service2_url						= '';
	$block2_service2_summary					= '';
	$block2_service3_title						= '';
	$block2_service3_url						= '';
	$block2_service3_summary					= '';
	$block2_service4_title						= '';
	$block2_service4_url						= '';
	$block2_service4_summary					= '';

	$block3_heading								= '';
	$block3_subheading							= '';
}


/**
 ***********************
 @@@ DELETE ME PHOTO @@@
 ***********************
 **/
if (isset($_REQUEST['delete_me_photo']) && $_REQUEST['delete_me_photo'] == 1) {

	$me_photo = get_photo_name('me_photo', 1, $tbl_name);

	if (!empty($me_photo)) {
		delete_photo_and_thumb($me_photo, $photo_upload_path);

		$rs = $mysqli->query("UPDATE `$tbl_name` SET me_photo='' WHERE id=1");
	}
}

/**
 ***************************
 @@@ DELETE BLOCK2 PHOTO @@@
 ***************************
 **/
if (isset($_REQUEST['delete_block2_service_photo']) && $_REQUEST['delete_block2_service_photo'] == 1) {

	$block2_service_photo = get_photo_name('block2_service_photo', 1, $tbl_name);

	if (!empty($block2_service_photo)) {
		delete_photo_and_thumb($block2_service_photo, $photo_upload_path);

		$rs = $mysqli->query("UPDATE `$tbl_name` SET block2_service_photo='' WHERE id=1");
	}
}


/**
 **************
 @@@ UPDATE @@@
 **************
 **/
if ($action == "update_$module") {

	if (empty($me_heading)) {
		$error_message = 'Me Heading is mandatory.';
	} else if (empty($me_subheading)) {
		$error_message = 'Me Subheading is mandatory.';
	} else if (empty($me_summary)) {
		$error_message = 'Me Summary is mandatory.';
	} else {
		/////// UPLOAD ME PHOTO ////////
		$me_photo = $_FILES["me_photo"]["name"];
		if (!empty($me_photo)) {
			$old_photo 				= get_photo_name('me_photo', $id, $tbl_name);
			$renamed 				= full_rename($me_photo, 'imranonline-me_photo');
			$message 				= upload_photo_without_thumb('me_photo', $renamed, $photo_upload_path, $allowed_file_size);
			if ($message) $error_message = $message;
			else 					$result = $mysqli->query("UPDATE `$tbl_name` SET me_photo='$renamed' WHERE id=1");
		}

		/////// UPLOAD BLOCK2 SERVICE PHOTO ////////
		$block2_service_photo = $_FILES["block2_service_photo"]["name"];
		if (!empty($block2_service_photo)) {
			$old_photo 				= get_photo_name('block2_service_photo', $id, $tbl_name);
			$renamed 				= full_rename($block2_service_photo, 'imranonline-block2_service_photo');
			$message 				= upload_photo_without_thumb('block2_service_photo', $renamed, $photo_upload_path, $allowed_file_size);
			if ($message) $error_message = $message;
			else 					$result = $mysqli->query("UPDATE `$tbl_name` SET block2_service_photo='$renamed' WHERE id=1");
		}

		//////////////////////////////////////////////////

		//Update Query
		$update_row = $mysqli->query("
		UPDATE `$tbl_name` SET
			me_heading					= '" . $me_heading . "',
			me_subheading				= '" . $me_subheading . "',
			me_summary					= '" . $me_summary . "',
			
			block1_service1_title		= '" . $block1_service1_title . "',
			block1_service1_url			= '" . $block1_service1_url . "',
			block1_service1_summary		= '" . $block1_service1_summary . "',
			block1_service2_title		= '" . $block1_service2_title . "',
			block1_service2_url			= '" . $block1_service2_url . "',
			block1_service2_summary		= '" . $block1_service2_summary . "',
			block1_service3_title		= '" . $block1_service3_title . "',
			block1_service3_url			= '" . $block1_service3_url . "',
			block1_service3_summary		= '" . $block1_service3_summary . "',
			block1_service4_title		= '" . $block1_service4_title . "',
			block1_service4_url			= '" . $block1_service4_url . "',
			block1_service4_summary		= '" . $block1_service4_summary . "',
			
			block2_heading				= '" . $block2_heading . "',
			block2_subheading			= '" . $block2_subheading . "',
			block2_service1_title		= '" . $block2_service1_title . "',
			block2_service1_url			= '" . $block2_service1_url . "',
			block2_service1_summary		= '" . $block2_service1_summary . "',
			block2_service2_title		= '" . $block2_service2_title . "',
			block2_service2_url			= '" . $block2_service2_url . "',
			block2_service2_summary		= '" . $block2_service2_summary . "',
			block2_service3_title		= '" . $block2_service3_title . "',
			block2_service3_url			= '" . $block2_service3_url . "',
			block2_service3_summary		= '" . $block2_service3_summary . "',
			block2_service4_title		= '" . $block2_service4_title . "',
			block2_service4_url			= '" . $block2_service4_url . "',
			block2_service4_summary		= '" . $block2_service4_summary . "',

			block3_heading				= '" . $block3_heading . "',
			block3_subheading			= '" . $block3_subheading . "'

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

$me_heading						= stripslashes($row_homepage['me_heading']);
$me_subheading					= stripslashes($row_homepage['me_subheading']);
$me_summary						= stripslashes($row_homepage['me_summary']);
$me_photo						= stripslashes($row_homepage['me_photo']);
$block2_service_photo			= stripslashes($row_homepage['block2_service_photo']);

$block1_service1_title			= stripslashes($row_homepage['block1_service1_title']);
$block1_service1_url			= stripslashes($row_homepage['block1_service1_url']);
$block1_service1_summary		= stripslashes($row_homepage['block1_service1_summary']);
$block1_service2_title			= stripslashes($row_homepage['block1_service2_title']);
$block1_service2_url			= stripslashes($row_homepage['block1_service2_url']);
$block1_service2_summary		= stripslashes($row_homepage['block1_service2_summary']);
$block1_service3_title			= stripslashes($row_homepage['block1_service3_title']);
$block1_service3_url			= stripslashes($row_homepage['block1_service3_url']);
$block1_service3_summary		= stripslashes($row_homepage['block1_service3_summary']);
$block1_service4_title			= stripslashes($row_homepage['block1_service4_title']);
$block1_service4_url			= stripslashes($row_homepage['block1_service4_url']);
$block1_service4_summary		= stripslashes($row_homepage['block1_service4_summary']);

$block2_heading					= stripslashes($row_homepage['block2_heading']);
$block2_subheading				= stripslashes($row_homepage['block2_subheading']);
$block2_service1_title			= stripslashes($row_homepage['block2_service1_title']);
$block2_service1_url			= stripslashes($row_homepage['block2_service1_url']);
$block2_service1_summary		= stripslashes($row_homepage['block2_service1_summary']);
$block2_service2_title			= stripslashes($row_homepage['block2_service2_title']);
$block2_service2_url			= stripslashes($row_homepage['block2_service2_url']);
$block2_service2_summary		= stripslashes($row_homepage['block2_service2_summary']);
$block2_service3_title			= stripslashes($row_homepage['block2_service3_title']);
$block2_service3_url			= stripslashes($row_homepage['block2_service3_url']);
$block2_service3_summary		= stripslashes($row_homepage['block2_service3_summary']);
$block2_service4_title			= stripslashes($row_homepage['block2_service4_title']);
$block2_service4_url			= stripslashes($row_homepage['block2_service4_url']);
$block2_service4_summary		= stripslashes($row_homepage['block2_service4_summary']);

$block3_heading					= stripslashes($row_homepage['block3_heading']);
$block3_subheading				= stripslashes($row_homepage['block3_subheading']);

$me_photo 						= get_photo_name('me_photo', 1, $tbl_name);
$block2_service_photo 			= get_photo_name('block2_service_photo', 1, $tbl_name);
///////////////////////////////////////////////////////////////////////////////
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

						<div class="col-md-4">
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
										<label class="col-lg-3 control-label"><strong>Me Heading</strong> <span class="f_req">*</span></label>
										<div class="col-lg-9">
											<input name="me_heading" id="me_heading" value="<?php echo $me_heading; ?>" class="form-control" type="text">
										</div>
									</div>

									<div class="form-group">
										<label class="col-lg-3 control-label"><strong>Me Subheading</strong> <span class="f_req">*</span></label>
										<div class="col-lg-9">
											<input name="me_subheading" id="me_subheading" value="<?php echo $me_subheading; ?>" class="form-control" type="text">
										</div>
									</div>

									<div class="form-group">
										<label class="col-lg-3 control-label"><strong>Me Summary</strong> <span class="f_req">*</span></label>
										<div class="col-lg-9">
											<textarea class="sepH_a form-control" rows="12" cols="1" id="me_summary" name="me_summary" onkeyup="javascript: char_count(this.id);"><?php echo $me_summary; ?></textarea>
											<span class="help-block"><strong><span id="span_me_summary"><?php echo strlen($me_summary); ?></span></strong> &nbsp; - &nbsp; 65 chars</span>
										</div>
									</div>

									<div class="form-group">
										<label class="col-lg-3 control-label"><strong>Me Photo</strong> <br />[750 x 650px]</label>
										<div class="col-lg-9">
											<input type="file" name="me_photo" id="me_photo" class="file-styled">
										</div>
									</div>

									<div class="form-group">
										<label class="col-lg-3 control-label">&nbsp;</label>
										<div class="col-lg-9">
											<?php if (!empty($me_photo)) { ?>
												<div class="form-group">
													<a href="<?php echo $photo_upload_path . $me_photo; ?>" target="_blank">
														<img src="<?php echo $photo_upload_path . $me_photo; ?>" width="150" alt="" />
													</a>
													<p><a href="<?php echo $module; ?>.php?delete_me_photo=1">
															<button type="button" class="btn btn-default btn-sm" name="delete_me_photo" id="delete_me_photo">Delete</button></a>
													</p>
												</div>
											<?php } ?>
										</div>
									</div>

									<hr />

									<div class="panel-heading">

										<div class="row">
											<div class="col-md-10">
												<div class="col-md-12">
													<h5>Block 3 - Service Tabs </h5>
												</div>
											</div>
										</div>

									</div>

									<div class="panel-body">

										<div class="form-group">
											<label class="col-lg-3 control-label"><strong>Heading</strong> <span class="f_req">*</span></label>
											<div class="col-lg-9">
												<input name="block3_heading" id="block3_heading" value="<?php echo $block3_heading; ?>" class="form-control" type="text">
											</div>
										</div>

										<div class="form-group">
											<label class="col-lg-3 control-label"><strong>Subheading</strong> <span class="f_req">*</span></label>
											<div class="col-lg-9">
												<input name="block3_subheading" id="block3_subheading" value="<?php echo $block3_subheading; ?>" class="form-control" type="text">
											</div>
										</div>
									</div>
								</div>
							</div>

						</div>

						<div class="col-md-4">
							<div class="panel panel-flat">
								<div class="panel-heading">

									<div class="row">
										<div class="col-md-7">
											<div class="col-md-12">
												<h5>Block 1</h5>
											</div>
										</div>
									</div>

								</div>
								<div class="panel-body">

									<div class="alert alert-info">
										<button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
										All Block 1 fields are <span class="text-bold">mandatory</span>.
									</div>

									<div class="form-group">
										<label class="col-lg-3 control-label"><strong>Service 1 Title</strong></label>
										<div class="col-lg-9">
											<input name="block1_service1_title" id="block1_service1_title" value="<?php echo $block1_service1_title; ?>" class="form-control" type="text">
										</div>
									</div>

									<div class="form-group">
										<label class="col-lg-3 control-label"><strong>Service 1 URL</strong></label>
										<div class="col-lg-9">
											<input name="block1_service1_url" id="block1_service1_url" value="<?php echo $block1_service1_url; ?>" class="form-control" type="text">
											<span class="help-block">Slug of service. e.g. website-development</span>
										</div>
									</div>

									<div class="form-group">
										<label class="col-lg-3 control-label"><strong>Service 1 Summary</strong></label>
										<div class="col-lg-9">
											<textarea class="sepH_a form-control" rows="3" cols="1" id="block1_service1_summary" name="block1_service1_summary"><?php echo $block1_service1_summary; ?></textarea>
										</div>
									</div>
									<hr />

									<div class="form-group">
										<label class="col-lg-3 control-label"><strong>Service 2 Title</strong></label>
										<div class="col-lg-9">
											<input name="block1_service2_title" id="block1_service2_title" value="<?php echo $block1_service2_title; ?>" class="form-control" type="text">
										</div>
									</div>

									<div class="form-group">
										<label class="col-lg-3 control-label"><strong>Service 2 URL</strong></label>
										<div class="col-lg-9">
											<input name="block1_service2_url" id="block1_service2_url" value="<?php echo $block1_service2_url; ?>" class="form-control" type="text">
											<span class="help-block">Slug of service. e.g. website-development</span>
										</div>
									</div>

									<div class="form-group">
										<label class="col-lg-3 control-label"><strong>Service 2 Summary</strong></label>
										<div class="col-lg-9">
											<textarea class="sepH_a form-control" rows="3" cols="1" id="block1_service2_summary" name="block1_service2_summary"><?php echo $block1_service2_summary; ?></textarea>
										</div>
									</div>
									<hr />

									<div class="form-group">
										<label class="col-lg-3 control-label"><strong>Service 3 Title</strong></label>
										<div class="col-lg-9">
											<input name="block1_service3_title" id="block1_service3_title" value="<?php echo $block1_service3_title; ?>" class="form-control" type="text">
										</div>
									</div>

									<div class="form-group">
										<label class="col-lg-3 control-label"><strong>Service 3 URL</strong></label>
										<div class="col-lg-9">
											<input name="block1_service3_url" id="block1_service3_url" value="<?php echo $block1_service3_url; ?>" class="form-control" type="text">
											<span class="help-block">Slug of service. e.g. website-development</span>
										</div>
									</div>

									<div class="form-group">
										<label class="col-lg-3 control-label"><strong>Service 3 Summary</strong></label>
										<div class="col-lg-9">
											<textarea class="sepH_a form-control" rows="3" cols="1" id="block1_service3_summary" name="block1_service3_summary"><?php echo $block1_service3_summary; ?></textarea>
										</div>
									</div>
									<hr />

									<div class="form-group">
										<label class="col-lg-3 control-label"><strong>Service 4 Title</strong></label>
										<div class="col-lg-9">
											<input name="block1_service4_title" id="block1_service4_title" value="<?php echo $block1_service4_title; ?>" class="form-control" type="text">
										</div>
									</div>

									<div class="form-group">
										<label class="col-lg-3 control-label"><strong>Service 4 URL</strong></label>
										<div class="col-lg-9">
											<input name="block1_service4_url" id="block1_service4_url" value="<?php echo $block1_service4_url; ?>" class="form-control" type="text">
											<span class="help-block">Slug of service. e.g. website-development</span>
										</div>
									</div>

									<div class="form-group">
										<label class="col-lg-3 control-label"><strong>Service 4 Summary</strong></label>
										<div class="col-lg-9">
											<textarea class="sepH_a form-control" rows="3" cols="1" id="block1_service4_summary" name="block1_service4_summary"><?php echo $block1_service4_summary; ?></textarea>
										</div>
									</div>

								</div>

							</div>
						</div>


						<div class="col-md-4">
							<div class="panel panel-flat">
								<div class="panel-heading">

									<div class="row">
										<div class="col-md-7">
											<div class="col-md-12">
												<h5>Block 2</h5>
											</div>
										</div>
									</div>

								</div>
								<div class="panel-body">

									<div class="form-group">
										<label class="col-lg-3 control-label"><strong>Heading</strong> <span class="f_req">*</span></label>
										<div class="col-lg-9">
											<input name="block2_heading" id="block2_heading" value="<?php echo $block2_heading; ?>" class="form-control" type="text">
										</div>
									</div>

									<div class="form-group">
										<label class="col-lg-3 control-label"><strong>Subheading</strong> <span class="f_req">*</span></label>
										<div class="col-lg-9">
											<input name="block2_subheading" id="block2_subheading" value="<?php echo $block2_subheading; ?>" class="form-control" type="text">
										</div>
									</div>

									<div class="form-group">
										<label class="col-lg-3 control-label"><strong>Photo</strong> <span class="f_req">*</span> <br />[750 x 650px]</label>
										<div class="col-lg-9">
											<input type="file" name="block2_service_photo" id="block2_service_photo" class="file-styled">
										</div>
									</div>

									<div class="form-group">
										<label class="col-lg-3 control-label">&nbsp;</label>
										<div class="col-lg-9">
											<?php if (!empty($block2_service_photo)) { ?>
												<div class="form-group">
													<a href="<?php echo $photo_upload_path . $block2_service_photo; ?>" target="_blank">
														<img src="<?php echo $photo_upload_path . $block2_service_photo; ?>" width="150" alt="" />
													</a>
													<p><a href="<?php echo $module; ?>.php?delete_block2_service_photo=1">
															<button type="button" class="btn btn-default btn-sm" name="delete_block2_service_photo" id="delete_block2_service_photo">Delete</button></a>
													</p>
												</div>
											<?php } ?>
										</div>
									</div>

									<div class="alert alert-info">
										<button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
										All Block 2 fields are <span class="text-bold">mandatory</span>.
									</div>

									<div class="form-group">
										<label class="col-lg-3 control-label"><strong>Service 1 Title</strong></label>
										<div class="col-lg-9">
											<input name="block2_service1_title" id="block2_service1_title" value="<?php echo $block2_service1_title; ?>" class="form-control" type="text">
										</div>
									</div>

									<div class="form-group">
										<label class="col-lg-3 control-label"><strong>Service 1 URL</strong></label>
										<div class="col-lg-9">
											<input name="block2_service1_url" id="block2_service1_url" value="<?php echo $block2_service1_url; ?>" class="form-control" type="text">
											<span class="help-block">Slug of service. e.g. website-development</span>
										</div>
									</div>

									<div class="form-group">
										<label class="col-lg-3 control-label"><strong>Service 1 Summary</strong></label>
										<div class="col-lg-9">
											<textarea class="sepH_a form-control" rows="3" cols="1" id="block2_service1_summary" name="block2_service1_summary"><?php echo $block2_service1_summary; ?></textarea>
										</div>
									</div>
									<hr />

									<div class="form-group">
										<label class="col-lg-3 control-label"><strong>Service 2 Title</strong></label>
										<div class="col-lg-9">
											<input name="block2_service2_title" id="block2_service2_title" value="<?php echo $block2_service2_title; ?>" class="form-control" type="text">
										</div>
									</div>

									<div class="form-group">
										<label class="col-lg-3 control-label"><strong>Service 2 URL</strong></label>
										<div class="col-lg-9">
											<input name="block2_service2_url" id="block2_service2_url" value="<?php echo $block2_service2_url; ?>" class="form-control" type="text">
											<span class="help-block">Slug of service. e.g. website-development</span>
										</div>
									</div>

									<div class="form-group">
										<label class="col-lg-3 control-label"><strong>Service 2 Summary</strong></label>
										<div class="col-lg-9">
											<textarea class="sepH_a form-control" rows="3" cols="1" id="block2_service2_summary" name="block2_service2_summary"><?php echo $block2_service2_summary; ?></textarea>
										</div>
									</div>
									<hr />

									<div class="form-group">
										<label class="col-lg-3 control-label"><strong>Service 3 Title</strong></label>
										<div class="col-lg-9">
											<input name="block2_service3_title" id="block2_service3_title" value="<?php echo $block2_service3_title; ?>" class="form-control" type="text">
										</div>
									</div>

									<div class="form-group">
										<label class="col-lg-3 control-label"><strong>Service 3 URL</strong></label>
										<div class="col-lg-9">
											<input name="block2_service3_url" id="block2_service3_url" value="<?php echo $block2_service3_url; ?>" class="form-control" type="text">
											<span class="help-block">Slug of service. e.g. website-development</span>
										</div>
									</div>

									<div class="form-group">
										<label class="col-lg-3 control-label"><strong>Service 3 Summary</strong></label>
										<div class="col-lg-9">
											<textarea class="sepH_a form-control" rows="3" cols="1" id="block2_service3_summary" name="block2_service3_summary"><?php echo $block2_service3_summary; ?></textarea>
										</div>
									</div>
									<hr />

									<div class="form-group">
										<label class="col-lg-3 control-label"><strong>Service 4 Title</strong></label>
										<div class="col-lg-9">
											<input name="block2_service4_title" id="block2_service4_title" value="<?php echo $block2_service4_title; ?>" class="form-control" type="text">
										</div>
									</div>

									<div class="form-group">
										<label class="col-lg-3 control-label"><strong>Service 4 URL</strong></label>
										<div class="col-lg-9">
											<input name="block2_service4_url" id="block2_service4_url" value="<?php echo $block2_service4_url; ?>" class="form-control" type="text">
											<span class="help-block">Slug of service. e.g. website-development</span>
										</div>
									</div>

									<div class="form-group">
										<label class="col-lg-3 control-label"><strong>Service 4 Summary</strong></label>
										<div class="col-lg-9">
											<textarea class="sepH_a form-control" rows="3" cols="1" id="block2_service4_summary" name="block2_service4_summary"><?php echo $block2_service4_summary; ?></textarea>
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