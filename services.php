<?php
include('admin_elements/admin_header.php');
$module = 'services';
$module_caption = 'Service';
$tbl_name = $tbl_prefix . $module;
$photo_upload_path = '../uploads/' . $module . '/';
$allowed_file_size = $GLOBALS['PHOTO']['MAX_UPLOAD_SIZE']; //MB Bytes
$thumb_width = '170';
$thumb_height = '135';
$error_message = '';
$success_message = '';
#########################################
if (isset($_POST['publish'])) 							$publish = 1;
else $publish = 0;
if (isset($_POST['views']) && !empty($_POST['views'])) 	$views = $mysqli->real_escape_string(stripslashes($_POST['views']));
else $views = 0;
if (isset($_POST['bit']) && !empty($_POST['bit'])) 		$bit = $mysqli->real_escape_string(stripslashes($_POST['bit']));
else $bit = 0;

/**
 **************************************
   @@@ GET ALL VARIABLES ADD/UPDATE @@@
 **************************************
 **/
if ($action == "update_$module" || $action == "add_$module") {
	$slug						= $mysqli->real_escape_string(stripslashes($_POST['slug']));
	$service_name				= $mysqli->real_escape_string(stripslashes($_POST['service_name']));
	$summary					= $mysqli->real_escape_string(stripslashes($_POST['summary']));
	$service_description		= $mysqli->real_escape_string(stripslashes($_POST['service_description']));
	$meta_title					= $mysqli->real_escape_string(stripslashes($_POST['meta_title']));
	$meta_description			= $mysqli->real_escape_string(stripslashes($_POST['meta_description']));
} else {
	$slug						= '';
	$service_name				= '';
	$summary					= '';
	$service_description		= '';
	$meta_title					= '';
	$meta_description			= '';
}

/**
 *************************
	@@@ DELETE PHOTO ONLY @@@
 *************************
 **/
if (isset($_REQUEST['delete_photo']) && $_REQUEST['delete_photo'] == 1 && !empty($id)) {
	$photo =	get_photo_name('photo', $id, $tbl_name);
	if (!empty($photo)) {
		delete_photo($photo, $photo_upload_path, '1'); 		// DELETE OLD THUMB
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

	if (empty($service_name)) {
		$error_message = 'Service name is mandatory.';
	} else if (empty($service_description)) {
		$error_message = 'Service description is mandatory.';
	} else if (empty($slug)) {
		$error_message = 'Slug is mandatory.';
	} else {

		// UPDATE SLUG - CHECK IN DB for DUPLICATES
		$result_slug 	= $mysqli->query("SELECT slug FROM `$tbl_name` WHERE id=$id");
		$row_slug 		= $result_slug->fetch_array();
		$db_slug		= stripslashes($row_slug['slug']);

		//ONLY UPDATE SLUG IF IT IS CHANGE
		if ($db_slug != $slug) {
			$result_s 	= $mysqli->query("SELECT id FROM `$tbl_name` WHERE slug='" . $slug . "'");
			$row_s 		= $result_s->fetch_array();

			// IF DUPLICATE SLUG FOUND MAKE IT UNIQUE
			if (!empty($row_s['id'])) {
				$slug = generate_service_slug($service_name);
				$mysqli->query("UPDATE `$tbl_name` SET slug	= '" . $slug . "' WHERE id=$id");

				// UPDATED UNIQUE ENTERED SLUG
			} else {
				$mysqli->query("UPDATE `$tbl_name` SET slug	= '" . $slug . "' WHERE id=$id");
			}
		}

		
		/////// UPLOAD PHOTO ////////
		$photo = $_FILES["photo"]["name"];

		if (!empty($photo)) {
			$old_photo 		= get_photo_name('photo', $id, $tbl_name);
			$renamed 		= full_rename($photo, $slug);
			$message 		= upload_photo_with_thumb('photo', $renamed, $photo_upload_path, $allowed_file_size, $old_photo, $thumb_width, $thumb_height);
			if ($message)	$error_message = $message;
			else			$result = $mysqli->query("UPDATE `$tbl_name` SET photo='$renamed' WHERE id=$id");
		} //endif

		//////////////////////////////////////////////////
		$update_row = $mysqli->query("
							UPDATE `$tbl_name` SET
								slug					= '" . $slug . "',
								service_name			= '" . $service_name . "',
								summary					= '" . $summary . "',
								service_description		= '" . $service_description . "',
								meta_title				= '" . $meta_title . "',
								meta_description		= '" . $meta_description . "',
								publish 				= '" . $publish . "'
							WHERE id=$id");

		if ($update_row) {
			echo $success_message = "$module_caption Updated Successfully.";
			fp__($tbl_name, $id);
			// header("Location:listing_$module.php?success_message=$success_message");
		} else {
			echo $error_message = "Sorry ! $module_caption Could Not Be Updated.";
			// header("Location:$module.php?action=edit_$module&id=$id&error_message=$error_message");
		}
	}

	/**
	 ***********
	@@@ ADD @@@
	 ***********
	 **/
} else if ($action == "add_$module") {

	if (empty($service_name)) {
		$error_message = 'Service name is mandatory.';
	} else if (empty($service_description)) {
		$error_message = 'Service description is mandatory.';
	} else {

		$slug 	= generate_service_slug($service_name);
		$bit 	= generateRandomString(9);

		$insert_row = $mysqli->query("INSERT INTO `$tbl_name`(slug, bit, service_name, summary, service_description, meta_title, meta_description, publish) VALUES ('" . $slug . "', '" . $bit . "', '" . $service_name . "', '" . $summary . "', '" . $service_description . "', '" . $meta_title . "', '" . $meta_description . "', '" . $publish . "'); ");

		if ($insert_row) {
			$id = $mysqli->insert_id;
			fp__($tbl_name, $id);
			$success_message = "$module_caption Saved Successfully.";
			/////// UPLOAD PHOTO ////////
			$photo = $_FILES["photo"]["name"];
			if (!empty($photo)) {
				$renamed_photo 	= full_rename($photo, $slug);
				$message 		= upload_photo_with_thumb('photo', $renamed_photo, $photo_upload_path, $allowed_file_size, '', $thumb_width, $thumb_height);
				if ($message)	$error_message = $message;
				else			$result = $mysqli->query("UPDATE `$tbl_name` SET photo='$renamed_photo' WHERE id=$id");
			} //endif
			// if ($message) 	$error_message= $message;
			// else  			header("Location:listing_$module.php?success_message=$success_message");
			header("Location:listing_$module.php?success_message=$success_message");
			//////////////////////////////////////////////////
		} else {
			$error_message = "Sorry ! $module_caption could not Save.";
			// header("Location:$module.php?error_message=$error_message");
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
	$service_name				= stripslashes($row['service_name']);
	$summary					= stripslashes($row['summary']);
	$service_description		= stripslashes($row['service_description']);
	$views						= stripslashes($row['views']);
	$meta_title					= stripslashes($row['meta_title']);
	$meta_description			= stripslashes($row['meta_description']);
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
										<h5><?php echo $module_caption; ?> Details <?php if (!empty($slug)) { ?><a href="<?php echo $base_url; ?>/service/<?php echo $slug; ?>" target="_blank">&#128065;</a><?php } ?></h5>
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
									<label class="col-lg-3 control-label"><strong>Service Name</strong> <span class="f_req">*</span></label>
									<div class="col-lg-9">
										<input name="service_name" id="service_name" value="<?php echo $service_name; ?>" class="form-control" type="text">
									</div>
								</div>

								<div class="form-group">
									<label class="col-lg-3 control-label"><strong>Slug</strong></label>
									<div class="col-lg-9">
										<input name="slug" id="slug" value="<?php echo $slug; ?>" class="form-control" type="text" onkeyup="javascript: char_count(this.id);" maxlength="160">
										<span class="help-block"><strong><span id="span_slug"><?php echo strlen($slug); ?></span></strong> &nbsp; - &nbsp; Unique 200 characters</span>
									</div>
								</div>

								<div class="form-group">
									<label class="col-lg-3 control-label"><strong>Summary</strong></label>
									<div class="col-lg-9">
										<textarea class="sepH_a form-control" rows="2" cols="1" id="summary" name="summary" onkeyup="javascript: char_count(this.id);"><?php echo $summary; ?></textarea>
										<span class="help-block"><strong><span id="span_summary"><?php echo strlen($summary); ?></span></strong> &nbsp; - &nbsp; Max 120 characters</span>
									</div>
								</div>

								<div class="form-group">
									<label class="col-lg-3 control-label"><strong>Photo</strong><br/>[<?php echo $thumb_width; ?>px x <?php echo $thumb_height; ?>px]</label>
									<div class="col-lg-9">
										<input type="file" name="photo" id="photo" class="file-styled">
										<span class="help-block">Accepted formats: gif, png, jpg. Max file size 5Mb</span>
									</div>
								</div>

								<div class="form-group">
									<label class="col-lg-3 control-label">&nbsp;</label>
									<div class="col-lg-9">
										<?php if (!empty($photo) && $photo != 'noimage.png') { ?>
											<div class="form-group">
												<a href="<?php echo $photo_upload_path . $photo ?>" target="_blank">
													<img src="<?php echo $photo_upload_path . $photo ?>" alt="" width="170" />
												</a>
												<p><a href="<?php echo $module; ?>.php?action=<?php echo $action; ?>&id=<?php echo $id; ?>&delete_photo=1';">
														<button type="button" class="btn btn-default btn-sm" name="delete_photo" id="delete_photo">Delete Photo</button></a>
												</p>
											</div>
										<?php } ?>
									</div>
								</div>

								<div class="form-group">
									<label class="col-lg-3 control-label"><strong>Meta Title</strong> </label>
									<div class="col-lg-9">
										<input name="meta_title" id="meta_title" value="<?php echo $meta_title; ?>" class="form-control" type="text" onkeyup="javascript: char_count(this.id);" maxlength="65">
										<span class="help-block"><strong><span id="span_meta_title"><?php echo strlen($meta_title); ?></span></strong> &nbsp; - &nbsp; Max 65 characters</span>
									</div>
								</div>

								<div class="form-group">
									<label class="col-lg-3 control-label"><strong>Meta Description</strong> </label>
									<div class="col-lg-9">
										<input name="meta_description" id="meta_description" value="<?php echo $meta_description; ?>" class="form-control" type="text" onkeyup="javascript: char_count(this.id);" maxlength="160">
										<span class="help-block"><strong><span id="span_meta_description"><?php echo strlen($meta_description); ?></span></strong> &nbsp; - &nbsp; Max 160 characters</span>
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
									<label><strong>Service description</strong> <span class="f_req">*</span></label>
									<textarea class="sepH_a form-control" rows="3" cols="1" id="service_description" name="service_description"><?php echo $service_description; ?></textarea>
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