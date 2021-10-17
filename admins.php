<?php
include('admin_elements/admin_header.php');
include('admin_elements/only_superadmin.php');
$module = 'admins';
$module_caption = 'User';
$tbl_name = $tbl_prefix . $module;
$error_message = '';
$success_message = '';
#########################################

if (isset($_POST['username']) && !empty($_POST['username'])) {
	$username = $mysqli->real_escape_string(stripslashes($_POST['username']));
} else {
	$username = '';
}
if (isset($_POST['password']) && !empty($_POST['password'])) {
	$password = $mysqli->real_escape_string(stripslashes($_POST['password']));
} else {
	$password = '';
}
if (isset($_POST['confirm_password']) && !empty($_POST['confirm_password'])) {
	$confirm_password = $mysqli->real_escape_string(stripslashes($_POST['confirm_password']));
} else {
	$confirm_password = '';
}

if (isset($_POST['active'])) 			$active = 1;
else $active = 0;

/**
 **************************************
@@@ GET ALL VARIABLES ADD/UPDATE @@@
 **************************************
 **/
if ($action == "update_$module" || $action == "add_$module") {
	$type		 	= $mysqli->real_escape_string(stripslashes($_POST['type']));
	$full_name		= $mysqli->real_escape_string(stripslashes($_POST['full_name']));
	$email 			= $mysqli->real_escape_string(stripslashes($_POST['email']));
	$mobile 		= $mysqli->real_escape_string(stripslashes($_POST['mobile']));
	$gender 		= $mysqli->real_escape_string(stripslashes($_POST['gender']));
	$company_id 		= $mysqli->real_escape_string(stripslashes($_POST['company_id']));
	$comments 		= $mysqli->real_escape_string(stripslashes($_POST['comments']));
} else {
	$type		 	= '';
	$full_name 		= '';
	$email 			= '';
	$mobile 		= '';
	$gender 		= '';
	$company_id 		= '';
	$comments 		= '';
}

/**
 **************************************
@@@ UPDATE @@@
 **************************************
 **/
if ($action == "update_$module" && !empty($id)) {

	if (empty($email)) {
		$error_message = 'Please enter valid Email';
	} else if (!empty($email) && (!filter_var($email, FILTER_VALIDATE_EMAIL))) {
		$error_message = 'Please enter valid Email';
	} else if (checkDuplicateEmail($email, $id, $tbl_name) == true) {
		$error_message = 'Email Address already in our system. Please try another.';
	} else if (empty($full_name)) {
		$error_message = 'Please enter Full Name';
	} else if (empty($mobile)) {
		$error_message = 'Please enter Mobile';
	} else {
		//Update Query
		$update_row = $mysqli->query("
			UPDATE `$tbl_name` SET
			active				= '" . $active . "',
			full_name			= '" . $full_name . "',
			email				= '" . $email . "',
			mobile				= '" . $mobile . "',
			company_id				= '" . $company_id . "',
			gender				= '" . $gender . "',
			comments			= '" . $comments . "'
			WHERE id=$id");

		//Only Superadmin Can create Superadmins
		if ($_SESSION[$project_pre]['type'] == 'superadmin') {
			$mysqli->query("UPDATE `$tbl_name` SET type='" . $type . "' WHERE id=$id");
		}

		if ($update_row) {
			$success_message = "$module_caption Updated Successfully.";
			fp__($tbl_name, $id);
			header("Location:listing_$module.php?success_message=$success_message");
		} else {
			$error_message = "Sorry ! $module_caption Could Not Be Updated.";
			//header("Location:$module.php?action=edit_$module&id=$id&error_message=$error_message");
		}
	}
	/**
	 **************************************
@@@ EDIT @@@
	 **************************************
	 **/
} else if (($action == "edit_$module" && !empty($id))) {

	$result = $mysqli->query("SELECT * FROM `$tbl_name` WHERE id=$id");
	$row = $result->fetch_array();

	$type		 			= stripslashes($row['type']);
	$active 				= stripslashes($row['active']);
	$username 				= stripslashes($row['username']);
	$full_name 				= stripslashes($row['full_name']);
	$email 					= stripslashes($row['email']);
	$mobile 				= stripslashes($row['mobile']);
	$gender 				= stripslashes($row['gender']);
	$company_id 				= stripslashes($row['company_id']);
	$comments 				= stripslashes($row['comments']);

	/**
	 **************************************
@@@ ADD @@@
	 **************************************
	 **/
} else if ($action == "add_$module") {

	if (empty($username)) {
		$error_message = 'Username is mandatory.';
	} else if (strlen($username) < 3) {
		$error_message = 'Username should be at least 3 characters.';
	} else if (checkDuplicateUsername($username, $tbl_name) == true) {
		$error_message = 'Username already exists. Please try another.';
	} else if (empty($password)) {
		$error_message = 'Password is mandatory.';
	} else if (strlen($confirm_password) < 6) {
		$error_message = 'Password and Confirm Password Mismatch!';
	} else if (strlen($password) < 6) {
		$error_message = 'New Password should be at least 6 characters.';
	} else if ($confirm_password != $confirm_password) {
		$error_message = 'Password and Confirm Password Mismatch!';
	} else if (empty($email)) {
		$error_message = 'Please enter valid Email';
	} else if (!empty($email) && (!filter_var($email, FILTER_VALIDATE_EMAIL))) {
		$error_message = 'Please enter valid Email';
	}  else if (empty($company_id)) {
		$error_message = 'Please enter valid Company';
	} else if (checkDuplicateEmail($email, $id = '', $tbl_name) == true) {
		$error_message = 'Email Address already in our system. Please try another.';
	} else if (empty($full_name)) {
		$error_message = 'Please enter Full Name';
	} else if (empty($mobile)) {
		$error_message = 'Please enter Mobile';
	} else {

		//Insert Query

		$insert_row = $mysqli->query("INSERT INTO `$tbl_name`(active, username, password, full_name,type, email, mobile, gender, comments,company_id) VALUES ('" . $active . "', '" . $username . "', '" . md5($password) . "', '" . $full_name . "', '" . $type . "', '" . $email . "', '" . $mobile . "', '" . $gender . "', '" . $comments . "', '" . $company_id . "')");
		$insert_row_id = $mysqli->insert_id;

		//Only Superadmin Can create Superadmins
		if ($_SESSION[$project_pre]['type'] == 'superadmin') {
			$mysqli->query("UPDATE `$tbl_name` SET type='" . $type . "' WHERE id='$insert_row_id' ");
		}

		if ($insert_row) {
			$success_message = "$module_caption Saved Successfully.";
			fp__($tbl_name, $insert_row_id);
			header("Location:listing_$module.php?success_message=$success_message");
			//////////////////////////////////////////////////
		} else {
			$error_message = "Sorry ! $module_caption Could Not Be Saved.";
			//header("Location:$module.php?error_message=$error_message");
		}
	}
}
///////////////////////////////////////////////////////////////////////////////
?>
<div class="content-wrapper">
	<div class="content">

		<?php include('admin_elements/breadcrumb.php'); ?>

		<form method="post" id="frm<?php echo $module; ?>" name="frm<?php echo $module; ?>" action="<?php echo $module; ?>.php" class="form-horizontal">
			<?php if (($action == "edit_$module" || $action == "update_$module") && !empty($id)) { ?>
				<input type="hidden" name="action" id="action" value="update_<?php echo $module; ?>" />
				<input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
			<?php } else { ?>
				<input type="hidden" name="action" id="action" value="add_<?php echo $module; ?>" />
			<?php } ?>
			<div class="row">
				<div class="col-md-6">

					<!-- <div class="panel panel-flat"> -->
					<!-- <div class="panel-heading"><h5 class="panel-title">Profile Details for Username <strong><?php echo $_SESSION[$project_pre]['username']; ?></strong></h5></div> -->

					<!-- <div class="panel-body"> -->

					<div class="panel panel-flat">
						<div class="panel-heading">

							<div class="row">
								<div class="col-md-6">
									<h5><?php echo $module_caption; ?> Details</h5>
								</div>
								<!-- <div class="col-md-3 text-right">
<label>Manager</label>
<input type="checkbox" name="manager" id="manager" data-on-color="success" data-size="small" <?php if ($manager == '1') { ?>checked="checked"<?php } ?> />
</div> -->
								<div class="col-md-6 text-right">
									<label>Active</label>
									<input type="checkbox" name="active" id="active" data-on-color="success" data-size="small" <?php if ($active == '1') { ?>checked="checked" <?php } ?> />
								</div>
							</div>

							<div class="panel-body">

								<div class="form-group">
									<label class="control-label col-sm-3">Type<span class="f_req">*</span></label>
									<div class="col-sm-9">
										<select class="form-control" name="type" id="type">
											<?php if (($_SESSION[$project_pre]['type'] == "superadmin")) { ?>
												<option value="superadmin" <?php if ($type == 'superadmin') { ?>selected="selected" <?php } ?>>Super Administrator</option>
											<?php } ?>
											<option value="admin" <?php if ($type == 'admin') { ?>selected="selected" <?php } ?>>Company Admin</option>
											<option value="manager" <?php if ($type == 'manager') { ?>selected="selected" <?php } ?>>Manager</option>
											<option value="staff" <?php if ($type == 'staff') { ?>selected="selected" <?php } ?>>Staff</option>
										</select>
									</div>
								</div>


								<div class="form-group">
									<label class="control-label col-sm-3">Username <span class="f_req">*</span></label>
									<div class="col-sm-9">
										<?php if (($action == "edit_$module" || $action == "update_$module") && !empty($id)) { ?>
											<p class="form-control-static"><strong><?php echo $username; ?></strong></p>
										<?php } else { ?>
											<input name="username" id="username" class="input-xlarge form-control" type="text" value="<?php echo $username; ?>" />
											<span class="help-block">Should be at least 3 characters</span>
										<?php } ?>
									</div>
								</div>

								<?php if (($action == "edit_$module" || $action == "update_$module") && !empty($id)) { ?>
								<?php } else { ?>
									<div class="form-group">
										<label for="u_password" class="control-label col-sm-3">Password <span class="f_req">*</span></label>
										<div class="col-sm-9">
											<div class="sepH_b">
												<input name="password" id="password" class="input-xlarge form-control" type="password">
												<span class="help-block">Should be at least 6 characters</span>
											</div>

											<input name="confirm_password" id="confirm_password" class="input-xlarge form-control" type="password">
											<span class="help-block">Repeat password</span>
										</div>
									</div>
								<?php } ?>

								<div class="form-group">
									<label for="u_fname" class="control-label col-sm-3">Full name <span class="f_req">*</span></label>
									<div class="col-sm-9">
										<input name="full_name" id="full_name" class="input-xlarge form-control" type="text" value="<?php echo $full_name; ?>" />
									</div>
								</div>


								<div class="form-group">
									<label for="u_email" class="control-label col-sm-3">Email <span class="f_req">*</span></label>
									<div class="col-sm-9">
										<input name="email" id="email" class="input-xlarge form-control" type="text" value="<?php echo $email; ?>" />
									</div>
								</div>

<div class="form-group">
										<label class="col-lg-3 control-label"><strong>Company Name</strong><span class="f_req">*</span></label>
										<div class="col-lg-9">
											<select class="form-control" name="company_id" id="company_id">
												<option value=''>Please select</option>
												<?php
												$result = $mysqli->query("SELECT * FROM `" . $tbl_prefix . "companies` WHERE publish=1 ORDER BY company_name");
												while ($rows = $result->fetch_array()) {
												?>
													<option value="<?php echo $rows['id']; ?>" <?php if ($action == "edit_$module" && $rows['id'] == $company_id) { ?>selected <?php } else if ($rows['id'] == $company_id) { ?>selected <?php } ?>>
														<?php echo $rows['company_name']; ?>
													</option>
												<?php } ?>
											</select>
										</div>
									</div>
							
								<div class="form-group">
									<label for="u_email" class="control-label col-sm-3">Mobile <span class="f_req">*</span></label>
									<div class="col-sm-9">
										<input name="mobile" id="mobile" class="input-xlarge form-control" type="text" value="<?php echo $mobile; ?>" />
										<span class="help-block">format: +971501234567</span>
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-sm-3">Gender</label>
									<div class="col-sm-9">
										<label class="radio-inline">
											<input value="1" id="gender" name="gender" type="radio" <?php if ($gender == 1) { ?>checked="checked" <?php } ?> />
											Male </label>
										<label class="radio-inline">
											<input value="0" id="gender" name="gender" type="radio" <?php if ($gender == 0) { ?>checked="checked" <?php } ?> />
											Female </label>
									</div>
								</div>


								<div class="form-group">
									<label for="u_signature" class="control-label col-sm-3">Comments</label>
									<div class="col-sm-9">
										<textarea rows="4" name="comments" id="comments" class="form-control"><?php echo $comments; ?></textarea>
									</div>
								</div>

								<div class="text-left">
									<button type="submit" class="btn btn-info"><?php if (($action == "edit_$module" || $action == "update_$module") && !empty($id)) { ?>Update<?php } else { ?>Save<?php } ?> <?php echo $module_caption; ?></button>
									<a href="listing_<?php echo $module; ?>.php"><button type="button" class="btn btn-default"> Back to <?php echo ucfirst($module_caption); ?> Listing </button></a>
								</div>



							</div>
						</div>
		</form>

	</div>
</div>

</form>

<?php include('admin_elements/copyright.php'); ?>
</div>
</div>
<?php include('admin_elements/admin_footer.php'); ?>