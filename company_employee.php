	<?php
	include('admin_elements/admin_header.php');
	$module = 'admins';
	$module_caption = 'Employee Form';
	$tbl_name = $tbl_prefix . $module;
	$error_message = '';
	$success_message = '';
	#########################################
	if (isset($_POST['publish'])) 				$publish = 1;
	else $publish = 0;

	/**
	 **************************************
	@@@ GET ALL VARIABLES ADD/UPDATE @@@
	 **************************************
	 **/
	if ($action == "update_$module" || $action == "add_$module") {
		$company					= $mysqli->real_escape_string(stripslashes($_POST['company']));
		
		$username					= $mysqli->real_escape_string(stripslashes($_POST['username']));
		$email					= $mysqli->real_escape_string(stripslashes($_POST['email']));
	} else {
		$company					= '';
		
		$username					= '';
		$email					= '';
		$password					= '';
	}


	/**
	 **************************************
	@@@ UPDATE @@@
	 **************************************
	 **/
	if ($action == "update_$module" && !empty($id)) {

		if (empty($company) || $company == 'Please select') {
			$error_message = 'Company is mandatory.';
		} else if (empty($username)) {
			$error_message = 'name  is mandatory.';
		
		} else if (empty($email)) {
			$error_message = 'email is mandatory.';
		} 
		 else if (empty($password)) {
			$error_message = 'password is mandatory.';
		}else {

			//////////////////////////////////////////////////
			$update_row = $mysqli->query("
										UPDATE `$tbl_name` SET
										company_id							= '" . $company . "',
										
										username						= '" . $username . "',
										email							= '" . $email . "',
										password							= '" . $password . "'
										WHERE id=$id");
			if ($update_row) {
				$success_message = "$module_caption Updated Successfully.";
				fp__($tbl_name, $id);
				header("Location:listing_$module.php?success_message=$success_message");
			} else {
				$error_message = "Sorry ! $module_caption Could Not Be Updated.";
				//header("Location:$module.php?action=edit_$module&id=$id&error_message=$error_message");
			}
		} }

		/**
		 **************************************
	@@@ ADD @@@
		 **************************************
		 **/
	 else if ($action == "add_$module") {

		if (empty($company) || $company == 'Please select') {
			$error_message = 'Company Name is mandatory.';
		} else if (empty($username)) {
			$error_message = 'username is mandatory.';
		} else if (empty($email)) {
			$error_message = 'Email is mandatory.';
		} else {
			$insert_row = $mysqli->query("INSERT INTO `$tbl_name`(company_id, username, email,password,type) VALUES ('" . $company . "','" . $username . "',  '" . $email . "', '" . $password . "','admin'); ");

			if ($insert_row) {
				$id = $mysqli->insert_id;
				$success_message = "$module_caption Saved Successfully.";
				header("Location:listing_$module.php?success_message=$success_message");
				//////////////////////////////////////////////////
			} else {
				$error_message = "Sorry ! $module_caption Could Not Be Saved.";
				//header("Location:$module.php?error_message=$error_message");
			}
		}
	}


	/**
	 **************************************
	@@@ EDIT @@@
	 **************************************
	 **/

	if (!empty($id)) {

		$result = $mysqli->query("SELECT * FROM `$tbl_name` WHERE id=$id");
		$row = $result->fetch_array();

		$company							= stripslashes($row['company_id']);
		$username							= stripslashes($row['username']);
		
		$email								= stripslashes($row['email']);
		
		
	}

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

					<div class="col-md-8">
						<div class="panel panel-flat">
							<div class="panel-heading">

								<div class="row">
									<div class="col-md-3">
										<div class="col-md-12">
											<h5><?php echo $module_caption; ?> Details <?php if (!empty($slug)) { ?><a href="<?php echo $base_url; ?>/article/<?php echo $slug; ?>" target="_blank">&#128065;</a><?php } ?></h5>
										</div>
									</div>

									<div class="col-md-9 text-right">
										
										<div class="col-md-4">
											<a href="listing_<?php echo $module; ?>.php"><button type="button" class="btn btn-default">Listing</button></a>
										</div>
										<div class="col-md-4">
											<button type="submit" class="btn btn-info"><?php if (($action == "edit_$module" || $action == "update_$module") && !empty($id)) { ?>Update<?php } else { ?>Save<?php } ?></button>
										</div>

									</div>

								</div>

								<div class="panel-body">

									
<div class="form-group">
										<label class="col-lg-3 control-label"><strong>Company Name</strong><span class="f_req">*</span></label>
										<div class="col-lg-9">
											<select class="form-control" name="company" id="company">
												<option value=''>Please select</option>
												<?php
												$result = $mysqli->query("SELECT * FROM `" . $tbl_prefix . "companies` WHERE publish=1 ORDER BY company_name");
												while ($rows = $result->fetch_array()) {
												?>
													<option value="<?php echo $rows['id']; ?>" <?php if ($action == "edit_$module" && $rows['id'] == $company) { ?>selected <?php } else if ($rows['id'] == $company) { ?>selected <?php } ?>>
														<?php echo $rows['company_name']; ?>
													</option>
												<?php } ?>
											</select>
										</div>
									</div>
									
							 <div class="form-group">
                                    <label class="col-lg-3 control-label"><strong>Username</strong> <span class="f_req">*</span></label>
                                    <div class="col-lg-9">
                                       <input type="text"  class="form-control" id="username" name="username"  value="<?php echo $username;?>" />
                                  
                                    </div>
                                </div>

				 <div class="form-group">
                                    <label class="col-lg-3 control-label"><strong>Email</strong> <span class="f_req">*</span></label>
                                    <div class="col-lg-9">
                                      <input type="email"  class="form-control" id="email" name="email"  value="<?php echo $email;?>" />
                                    </div>
                                </div>				

									<div class="form-group">
										<label class="col-lg-3 control-label"><strong>Password</strong></label>
										<div class="col-lg-9">
											<input type="password" class="sepH_a form-control" id="password" name="password" /><?php //echo $file; ?>
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