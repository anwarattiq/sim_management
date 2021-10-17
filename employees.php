	<?php
	include('admin_elements/admin_header.php');
	$module = 'employees';
	$module_caption = 'Employee';
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
		$staff_id					= $mysqli->real_escape_string(stripslashes($_POST['staff_id']));
		$employee_name				= $mysqli->real_escape_string(stripslashes($_POST['employee_name']));
		$city						= $mysqli->real_escape_string(stripslashes($_POST['city']));
		$job_title					= $mysqli->real_escape_string(stripslashes($_POST['job_title']));
		$address					= $mysqli->real_escape_string(stripslashes($_POST['address']));
	} else {
		$company					= '';
		$staff_id					= '';
		$employee_name				= '';
		$city						= '';
		$job_title					= '';
		$address					= '';
	}


	/**
	 **************************************
	@@@ UPDATE @@@
	 **************************************
	 **/
	if ($action == "update_$module" && !empty($id)) {

		if (empty($company) || $company == 'Please select') {
			$error_message = 'Company is mandatory.';
		} else if (empty($employee_name)) {
			$error_message = 'Emplyee Name is mandatory.';
		} else if (empty($city) || $city == 'Please select') {
			$error_message = 'City is mandatory.';
		} else if (empty($job_title)) {
			$error_message = 'Job Title is mandatory.';
		} else {

			//////////////////////////////////////////////////
			$update_row = $mysqli->query("
										UPDATE `$tbl_name` SET
										company							= '" . $company . "',
										staff_id						= '" . $staff_id . "',
										employee_name					= '" . $employee_name . "',
										city							= '" . $city . "',
										job_title						= '" . $job_title . "',
										address							= '" . $address . "',
										publish 						= '" . $publish . "'
										WHERE id=$id");
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
	@@@ ADD @@@
		 **************************************
		 **/
	} else if ($action == "add_$module") {

		if (empty($company) || $company == 'Please select') {
			$error_message = 'Company Name is mandatory.';
		} else if (empty($employee_name)) {
			$error_message = 'Emplyee Name is mandatory.';
		} else if (empty($city) || $city == 'Please select') {
			$error_message = 'City Name is mandatory.';
		} else if (empty($job_title)) {
			$error_message = 'Job Title is mandatory.';
		} else {

			$insert_row = $mysqli->query("INSERT INTO `$tbl_name`(company, staff_id, employee_name, city, job_title, address, publish) VALUES ('" . $company . "','" . $staff_id . "', '" . $employee_name . "', '" . $city . "',  '" . $job_title . "', '" . $address . "', '" . $publish . "'); ");

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

		$company							= stripslashes($row['company']);
		$staff_id							= stripslashes($row['staff_id']);
		$employee_name						= stripslashes($row['employee_name']);
		$city								= stripslashes($row['city']);
		$job_title							= stripslashes($row['job_title']);
		$address							= stripslashes($row['address']);
		$publish 							= stripslashes($row['publish']);
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
											<input type="checkbox" name="publish" id="publish" data-on-color="success" data-size="small" <?php if ($publish == '1') { ?>checked="checked" <?php } ?> />
										</div>
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
										<label class="col-lg-3 control-label"><strong>Staff ID</strong></label>
										<div class="col-lg-9">
											<input name="staff_id" id="staff_id" value="<?php echo $staff_id; ?>" class="form-control" type="text">
										</div>
									</div>

									<div class="form-group">
										<label class="col-lg-3 control-label"><strong>Employee Name</strong><span class="f_req">*</span></label>
										<div class="col-lg-9">
											<input name="employee_name" id="employee_name" value="<?php echo $employee_name; ?>" class="form-control" type="text">
										</div>
									</div>


									<div class="form-group">
										<label class="col-lg-3 control-label"><strong>City Name</strong><span class="f_req">*</span></label>
										<div class="col-lg-9">
											<select class="form-control" name="city" id="city">
												<option value=''>Please select</option>
												<?php
												$result = $mysqli->query("SELECT * FROM `" . $tbl_prefix . "cities` WHERE publish=1 ORDER BY city_name");
												while ($rows = $result->fetch_array()) {
												?>
													<option value="<?php echo $rows['id']; ?>" <?php if ($action == "edit_$module" && $rows['id'] == $city) { ?>selected <?php } else if ($rows['id'] == $city) { ?>selected <?php } ?>>
														<?php echo $rows['city_name']; ?>
													</option>
												<?php } ?>
											</select>
										</div>
									</div>


									<div class="form-group">
										<label class="col-lg-3 control-label"><strong>Job Title</strong><span class="f_req">*</span></label>
										<div class="col-lg-9">
											<input name="job_title" id="job_title" value="<?php echo $job_title; ?>" class="form-control" type="text">
										</div>
									</div>

									<div class="form-group">
										<label class="col-lg-3 control-label"><strong>Address</strong></label>
										<div class="col-lg-9">
											<textarea class="sepH_a form-control" rows="3" cols="1" id="address" name="address"><?php echo $address; ?></textarea>
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