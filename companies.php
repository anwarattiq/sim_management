<?php
include('admin_elements/admin_header.php');
$module = 'companies';
$module_caption = 'Company';
$tbl_name = $tbl_prefix . $module;
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
	$company_name				= $mysqli->real_escape_string(stripslashes($_POST['company_name']));
		$city						= $mysqli->real_escape_string(stripslashes($_POST['city']));
} else {
	$company_name				= '';
		$city						= '';
}

/**
 **************
@@@ UPDATE @@@
 **************
 **/
if ($action == "update_$module" && !empty($id)) {

	if (empty($company_name)) {
		$error_message = 'Company Name is mandatory.';}
		else if (empty($city) || $city == 'Please select') {
			$error_message = 'City is mandatory.';
			
			
			
	} else if (($company_name !=$_REQUEST['company_name_old']) && checkDuplicateRow($tbl_name, 'company_name', $company_name)) {
		$error_message = 'Duplicate Company Name. Please enter different.';
	} else {

		//////////////////////////////////////////////////
		$update_row = $mysqli->query("
								UPDATE `$tbl_name` SET
									company_name	= '" . $company_name . "',
										city							= '" . $city . "',
									publish			= '" . $publish . "'
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

	if (empty($company_name)) {
		$error_message = 'Company Name is mandatory.';
		} else if (empty($city) || $city == 'Please select') {
			$error_message = 'City Name is mandatory.';
		
	} else if (checkDuplicateRow($tbl_name, 'company_name', $company_name)) {
		$error_message = 'Duplicate Company Name. Please enter different.';
	} else {

		$insert_row = $mysqli->query("INSERT INTO `$tbl_name`(company_name,city, publish) VALUES ('" . $company_name . "', '" . $city . "','" . $publish . "'); ");

		if ($insert_row) {
			$id = $mysqli->insert_id;
			$success_message = "$module_caption Saved Successfully.";
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

	$company_name		= stripslashes($row['company_name']);
	$publish 			= stripslashes($row['publish']);
	$city								= stripslashes($row['city']);
}
////////////////////////////////////////////////
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

				<div class="col-md-10">
					<div class="panel panel-flat">
						<div class="panel-heading">

							<div class="row">
								<div class="col-md-3">
									<div class="col-md-12">
										<h5><?php echo $module_caption; ?> </h5>
									</div>
								</div>

								<div class="col-md-9 text-right">
									<div class="col-md-10">
										<input type="checkbox" name="publish" id="publish" data-on-color="success" data-size="small" <?php if ($publish == '1') { ?>checked="checked" <?php } ?> />
									</div>
								
									
									
									<div class="col-md-2">
										<button type="submit" class="btn btn-info"><?php if (($action == "edit_$module" || $action == "update_$module") && !empty($id)) { ?>Update<?php } else { ?>Save<?php } ?></button>
									</div>
								</div>

							</div>

							<div class="panel-body">

								<div class="form-group">
									<label class="col-lg-3 control-label"><strong>Company Name</strong> <span class="f_req">*</span></label>
									<div class="col-lg-9">
										<input name="company_name" id="company_name" value="<?php echo $company_name; ?>" class="form-control" type="text">
											<input name="company_name_old" id="company_name_old" value="<?php echo $company_name; ?>" class="form-control" type="hidden">
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