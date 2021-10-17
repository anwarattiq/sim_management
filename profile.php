<?php
include('admin_elements/admin_header.php');
$module = 'admins';
$module_caption = 'My Profile';
$tbl_name = $tbl_prefix . $module;
$photo_upload_path = 'uploads/' . $module . '/';
$allowed_file_size = $GLOBALS['PHOTO']['MAX_UPLOAD_SIZE']; //MB Bytes
$thumb_width = '200';
$thumb_height = '150';
$error_message = '';
$success_message = '';
$id = $_SESSION[$project_pre]['admin_id'];
#########################################

/**
 **************************************
   @@@ GET ALL VARIABLES ADD/UPDATE @@@
 **************************************
 **/
if ($action == "update_$module") {
	$full_name 	= $mysqli->real_escape_string(stripslashes($_POST['full_name']));
	$email 			= $mysqli->real_escape_string(stripslashes($_POST['email']));
	$mobile 		= $mysqli->real_escape_string(stripslashes($_POST['mobile']));
	$gender 		= $mysqli->real_escape_string(stripslashes($_POST['gender']));
	$comments 	= $mysqli->real_escape_string(stripslashes($_POST['comments']));
} else {
	$full_name 	= '';
	$email 			= '';
	$mobile 		= '';
	$gender 		= '';
	$comments 	= '';
}

/**
 **************************************
		 	  	@@@ DELETE PHOTO ONLY @@@
 **************************************
 **/
if ($action == 'delete_photo' && !empty($id)) {
	$photo = get_photo_name('photo', $id, $tbl_name);
	if (!empty($photo)) {
		delete_photo($photo, "uploads/" . $module . "/", '1'); 	// DELETE OLD THUMB
		delete_photo($photo, "uploads/" . $module . "/", '0');		// DELETE OLD PHOTO

		$result = $mysqli->query("UPDATE `$tbl_name` SET photo='' WHERE id=$id");
		$success_message = 'Image Deleted Successfully.';
	}
}

/**
 **************************************
							@@@ UPDATE @@@
 **************************************
 **/
if ($action == "update_$module" && !empty($id)) {

	if (empty($full_name)) {
		$error_message = 'Full Name is mandatory.';
	} else if (empty($email)) {
		$error_message = 'Email is mandatory.';
	} else if (empty($mobile)) {
		$error_message = 'Mobile is mandatory.';
	} else {

		/////// UPLOAD PHOTO ////////
		$old_photo = get_photo_name('photo', $id, $tbl_name);
		$photo = $_FILES["photo"]["name"];

		if (!empty($photo)) {
			$old_photo 		= get_photo_name('photo', $id, $tbl_name);
			$renamed 		= full_rename($photo, $id);
			// $renamed_photo = renamePhoto($photo, $complete = 0);
			// $message = uploadPhoto('photo', $renamed_photo, $photo_upload_path, $allowed_file_size, $old_photo, '200', '150');
			$message = upload_photo_with_thumb('photo', $renamed, $photo_upload_path, $allowed_file_size, $old_photo, $thumb_width, $thumb_height);

			if ($message)
				$error_message = $message;
			else
				$result = $mysqli->query("UPDATE `$tbl_name` SET photo='$renamed' WHERE id=$id");
		} //endif

		//////////////////////////////////////////////////

		if (!empty($email) && (!filter_var($email, FILTER_VALIDATE_EMAIL))) {
			$error_message = 'Please enter valid Email';
		} else {

			//Update Query
			$update_row = $mysqli->query("
																UPDATE `$tbl_name` SET
																	full_name	= '" . $full_name . "',
																	email		= '" . $email . "',
																	mobile		= '" . $mobile . "',
																	gender		= '" . $gender . "',
																	comments	= '" . $comments . "'
																WHERE id='" . $id . "'");
			if ($update_row) {
				$success_message = "$module_caption Updated Successfully.";
				fp__($tbl_name, $id);
				//header("Location:listing_$module.php?success_message=$success_message");
			} else {
				$error_message = "Sorry ! $module_caption Could Not Be Updated.";
				//header("Location:$module.php?action=edit_$module&id=$id&error_message=$error_message");
			}
		}
	}
}

/**
 **************************************
					 	   @@@ EDIT @@@
 **************************************
 **/
$result = $mysqli->query("SELECT * FROM `$tbl_name` WHERE id='" . $id . "'");
$row = $result->fetch_array();

$id					= stripslashes($row['id']);
$full_name 	= stripslashes($row['full_name']);
$email 			= stripslashes($row['email']);
$mobile 		= stripslashes($row['mobile']);
$gender 		= stripslashes($row['gender']);
$comments 	= stripslashes($row['comments']);


$photo = get_photo_name('photo', $id, $tbl_name)
///////////////////////////////////////////////////////////////////////////////
?>
<div class="content-wrapper">
	<div class="content">

		<?php include('admin_elements/breadcrumb.php'); ?>

		<form method="post" id="frm<?php echo $module; ?>" name="frm<?php echo $module; ?>" action="profile.php" class="form-horizontal" enctype="multipart/form-data">
			<input type="hidden" name="action" id="action" value="update_<?php echo $module; ?>" />
			<input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
			<div class="row">
				<div class="col-md-6">

					<div class="panel panel-flat">
						<div class="panel-heading">
							<h5 class="panel-title">Profile Details - <?php echo $_SESSION[$project_pre]['username']; ?></h5>
						</div>

						<div class="panel-body">
							<div class="form-group">
								<label>Full Name:</label>
								<input type="text" class="form-control" name="full_name" id="full_name" value="<?php echo $full_name; ?>" />
							</div>

							<div class="form-group">
								<label>Email:</label>
								<input type="text" class="form-control" name="email" id="email" value="<?php echo $email; ?>" />
							</div>

							<div class="form-group">
								<label>Mobile:</label>
								<input type="text" class="form-control" name="mobile" id="mobile" value="<?php echo $mobile; ?>" />
							</div>

							<div class="form-group">
								<label class="display-block">Gender:</label>

								<label class="radio-inline">
									<input type="radio" class="styled" value="1" id="gender" name="gender" <?php if ($gender == 1) { ?>checked="checked" <?php } ?> />
									Male
								</label>

								<label class="radio-inline">
									<input type="radio" class="styled" value="0" id="gender" name="gender" <?php if ($gender == 0) { ?>checked="checked" <?php } ?> />
									Female
								</label>
							</div>

							<div class="form-group">
								<label>Comments:</label>
								<textarea rows="5" cols="5" class="form-control" name="comments" id="comments"><?php echo $comments; ?></textarea>
							</div>

							<div class="text-right">
								<button class="btn btn-primary" type="submit">Update</button>
							</div>
						</div>
					</div>
		</form>

	</div>
	<div class="col-md-6">
		<div class="panel panel-flat">

			<div class="panel-body">
				<div class="form-group">
					<label>Your avatar:</label>
					<input type="file" name="photo" id="photo" class="file-styled">

					<span class="help-block">Accepted formats: gif, png, jpg. Max file size 2Mb</span>
				</div>

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
	</div>

</div>
</form>

<?php include('admin_elements/copyright.php'); ?>
</div>
</div>
<?php include('admin_elements/admin_footer.php'); ?>