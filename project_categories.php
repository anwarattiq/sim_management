<?php
include('admin_elements/admin_header.php');
$module = 'project_categories';
$module_caption = 'Project Category';
$tbl_name = $tbl_prefix . $module;

$photo_upload_path = '../uploads/' . $module . '/';
$allowed_file_size = $GLOBALS['PHOTO']['MAX_UPLOAD_SIZE']; //MB Bytes
$thumb_width = '200';
$thumb_height = '154';
$image_width = '650';
$image_height = '500';


$error_message = '';
$success_message = '';
#########################################

if (isset($_POST['publish'])) 				$publish = 1;
else $publish = 0;;


/**
 ************************************
@@@ GET ALL VARIABLES ADD/UPDATE @@@
 ************************************
 **/
if ($action == "update_$module" || $action == "add_$module") {
	$category				= $mysqli->real_escape_string(stripslashes($_POST['category']));
	$slug					= $mysqli->real_escape_string(stripslashes($_POST['slug']));
} else {
	$category				= '';
	$slug					= '';
}

/**
 *************************
	@@@ DELETE PHOTO ONLY @@@
 *************************
 **/
if (isset($_REQUEST['delete_photo']) && $_REQUEST['delete_photo'] == 1 && !empty($id)) {
	$photo =	get_photo_name('photo', $id, $tbl_name);
	if (!empty($photo)) {

		// DELETE OLD PHOTO
		delete_photo($photo, $photo_upload_path);

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

	if (empty($category) || $category == 'Please select') {
		$error_message = 'Category is mandatory.';
	} else {

		// UPDATE SLUG - CHECK IN DB for DUPLICATES
		$result_slug 	= $mysqli->query("SELECT slug FROM `$tbl_name` WHERE id=$id");
		$row_slug 		= $result_slug->fetch_array();
		$db_slug		= stripslashes($row_slug['slug']);

		//ONLY UPDATE SLUG IF IT IS CHANGE
		if ($db_slug != $slug) {
			$result_s = $mysqli->query("SELECT id FROM `$tbl_name` WHERE slug='" . $slug . "'");
			$row_s 		= $result_s->fetch_array();

			// IF DUPLICATE SLUG FOUND MAKE IT UNIQUE
			if (!empty($row_s['id'])) {
				$slug = generate_project_category_slug($category);
				$mysqli->query("UPDATE `$tbl_name` SET slug	= '" . $slug . "' WHERE id=$id");

				// UPDATED UNIQUE ENTERED SLUG
			} else {
				$mysqli->query("UPDATE `$tbl_name` SET slug	= '" . $slug . "' WHERE id=$id");
			}
		}

		/////// UPLOAD PHOTO ////////
		$old_photo =	get_photo_name('photo', $id, $tbl_name);
		$photo = $_FILES["photo"]["name"];

		if (!empty($photo)) {
			$renamed_photo 		= full_rename($photo, $slug);
			$message 			= upload_photo_with_thumb('photo', $renamed_photo, $photo_upload_path, $allowed_file_size, $old_photo, $thumb_width, $thumb_height);
			if ($message)		$error_message = $message;
			else				$result = $mysqli->query("UPDATE `$tbl_name` SET photo='$renamed_photo' WHERE id=$id");
		} //endif

		//////////////////////////////////////////////////
		$update_row = $mysqli->query("
								UPDATE `$tbl_name` SET
									category		= '" . $category . "',
									slug			= '" . $slug . "',
									publish 		= '" . $publish . "'
								WHERE id=$id");
		if ($update_row) {
			$success_message = "$module_caption Updated Successfully.";
			fp__($tbl_name, $id);
			header("Location:$module.php?success_message=$success_message");
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

	if (empty($category) || $category == 'Please select') {
		$error_message = 'Category is mandatory.';
	} else if (checkDuplicateRow($tbl_name, 'category', $category)) {
		$error_message = 'Duplicate Category. Please enter different.';
	} else {

		$slug 	= generate_project_category_slug($category);

		$insert_row = $mysqli->query("INSERT INTO `$tbl_name`(category, slug, publish) VALUES ('" . $category . "', '" . $slug . "', '" . $publish . "'); ");

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
			header("Location:listing_$module.php?success_message=$success_message");
			//////////////////////////////////////////////////
		} else {
			$error_message = "Sorry ! $module_caption Could Not Be Saved.";
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

	$category							= stripslashes($row['category']);
	$slug								= stripslashes($row['slug']);
	$publish 							= stripslashes($row['publish']);
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
										<h5><?php echo $module_caption; ?> </h5>
									</div>
								</div>

								<div class="col-md-9 text-right">
									<div class="col-md-8">
										<input type="checkbox" name="publish" id="publish" data-on-color="success" data-size="small" <?php if ($publish == '1') { ?>checked="checked" <?php } ?> />
									</div>
									<div class="col-md-2">
										<a href="listing_<?php echo $module; ?>.php"><button type="button" class="btn btn-default"> Listing </button></a>
									</div>
									<div class="col-md-2">
										<button type="submit" class="btn btn-info"><?php if (($action == "edit_$module" || $action == "update_$module") && !empty($id)) { ?>Update<?php } else { ?>Save<?php } ?></button>
									</div>
								</div>

							</div>

							<div class="panel-body">

								<div class="form-group">
									<label class="col-lg-3 control-label"><strong>Category</strong> <span class="f_req">*</span></label>
									<div class="col-lg-9">
										<input name="category" id="category" value="<?php echo $category; ?>" class="form-control" type="text">
									</div>
								</div>


								<div class="form-group">
									<label class="col-lg-3 control-label"><strong>Slug</strong></label>
									<div class="col-lg-9">
										<input name="slug" id="slug" value="<?php echo $slug; ?>" class="form-control" type="text" onkeyup="javascript: char_count(this.id);" maxlength="160">
										<span class="help-block"><strong><span id="span_slug"><?php echo strlen($slug); ?></span></strong> &nbsp; - &nbsp; Unique 200 chars (auto-generated)</span>
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
												<p><a href="<?php echo $module; ?>.php?action=<?php echo $action; ?>&id=<?php echo $id; ?>&delete_photo=1;">
														<button type="button" class="btn btn-default btn-sm" name="delete_photo" id="delete_photo">Delete Photo</button></a>
												</p>
											</div>
										<?php } ?>
									</div>
								</div>

							</div>


						</div>
					</div>

				</div>


				<!-- <div class="col-md-6">
					<div class="panel panel-flat">
						<div class="panel-heading">

							<div class="panel-body">

							</div>

						</div>
					</div>
				</div> -->


			</div>
		</form>

		<?php include('admin_elements/copyright.php'); ?>
	</div>
</div>
<?php include('admin_elements/admin_footer.php'); ?>