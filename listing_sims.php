<?php
include('admin_elements/admin_header.php');
$module = 'sims';
$module_caption = 'Sim Number';
$tbl_name = $tbl_prefix . $module;
$photo_upload_path = '../uploads/' . $module . '/';
$error_message = '';
$success_message = '';
#########################################

/**
 ****************
	@@@ FEATURED @@@
 ****************
 **/
if (($action == "featured_$module" && !empty($id))) {

	$result = $mysqli->query("UPDATE `$tbl_name` SET featured=1 WHERE id=$id");
	if ($result)
		$success_message = "$module_caption set featured Successfully.";
	else
		$error_message = "Sorry! $module Could Not Be set featured.";

	/**
	 ********************
	@@@ NOT FEATURED @@@
	 ********************
	 **/
} else if (($action == "nofeatured_$module" && !empty($id))) {

	$result = $mysqli->query("UPDATE `$tbl_name` SET featured=0 WHERE id=$id");
	if ($result)
		$success_message = "$module_caption set as not featured Successfully.";
	else
		$error_message = "Sorry! $module Could Not Be set as not featured.";

	/**
	 ***************
	@@@ PUBLISH @@@
	 ***************
	 **/
} else if (($action == "publish_$module" && !empty($id))) {

	if (publish($module_caption, $tbl_name, $id))
		$success_message = "$module_caption Published Successfully.";
	else
		$error_message = "Sorry! $module Could Not Be Published.";

	/**
	 ***************
	@@@ PUBLISH @@@
	 ***************
	 **/
} else if (($action == "unpublish_$module" && !empty($id))) {

	if (unpublish($module_caption, $tbl_name, $id))
		$success_message = "$module_caption Un-Published Successfully.";
	else
		$error_message = "Sorry! $module Could Not Be Un-Published.";


	/**
	 **************
	@@@ DELETE @@@
	 **************
	 **/
} else if (($action == "delete_$module" && !empty($id))) {

	//SUPERADMIN CAN DELETE ANY DATA
	if ($_SESSION[$project_pre]['type'] == 'superadmin') {

		$photo = get_photo_name('photo', $id, $tbl_name);
		$result = $mysqli->query("DELETE FROM `$tbl_name` WHERE id=$id");

		// DELETE OLD PHOTO
		if (!empty($photo)) delete_photo($photo, $photo_upload_path);

		//ADMIN CAN DELETE ONLY HIS/HER DATA
	} else {

		$photo = get_photo_name('photo', $id, $tbl_name);
		$result = $mysqli->query("DELETE FROM `$tbl_name` WHERE id=$id AND created_by='" . $_SESSION[$project_pre]['admin_id'] . "'");

		// DELETE OLD PHOTO
		if (!empty($photo)) delete_photo($photo, $photo_upload_path);
	}


	if ($result) {
		$success_message = "$module_caption Deleted Successfully.";
		header("Location:listing_$module.php?page=$page&success_message=$success_message");
	} else {
		$error_message = "Sorry! $module Could Not Be Deleted.";
	}
}
///////////////////////////////////////////////////////////////////////////////
?>
<div class="content-wrapper">
	<div class="page-header page-header-default">
		<div class="page-header-content">
			<div class="page-title">
				<h4><i class="icon-circle-right2 position-left"></i> Listing <?php echo '<strong>' . ucwords($type) . '</strong>'; ?> <?php echo $module_caption; ?></h4>
				<a class="heading-elements-toggle"><i class="icon-more"></i></a>
			</div>
			<div class="heading-elements">
				<div class="heading-btn-group">
					<a href="<?php echo $module; ?>.php"><button class="btn btn-info">Add <?php echo $module_caption; ?></button></a>
				</div>
			</div>
		</div>
	</div>
	<div class="content">

		<?php include('admin_elements/breadcrumb.php'); ?>

		<div class="row">
			<div class="col-md-12">

				<!-- <div class="text-center"><a href="<?php echo $module ?>.php"><button class="btn btn-info">Add <?php echo $module_caption; ?></button></a></div> -->

				<table id="grid-<?php echo $module; ?>" class="display responsive no-wrap table table-striped table-bordered table-hover">
					<thead>
						<tr>
							<th width="40">Manage</th>
							<th width="100">Telcom Name</th>
							<th>SIM Number</th>
								<th>Employee Name</th>
							<th>Package Name</th>
							<th>Amount (IQD)</th>
							<!-- <th>Created</th> -->
							<!-- <th></th> -->
						</tr>
					</thead>
				</table>

			</div>
		</div>
		<div class="alert alert-info alert-styled-left alert-arrow-left alert-component">
			<ul>
				<li><button class="btn btn-info">11 digits</button></a> Standard format for SIM Number.</li>
			</ul>
		</div>
		<?php include('admin_elements/copyright.php'); ?>
	</div>
</div>
<?php include('admin_elements/admin_footer.php'); ?>