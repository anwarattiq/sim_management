<?php
include('admin_elements/admin_header.php');
$module = 'projects';
$module_caption = 'Project';
$tbl_name = $tbl_prefix . $module;
$photo_upload_path = '../uploads/' . $module . '/';
$error_message = '';
$success_message = '';
#########################################


/**
****************
@@@ HOMEPAGE @@@
****************
**/
if (($action == "home_$module" && !empty($id))) {

	$result = $mysqli->query("UPDATE `$tbl_name` SET homepage=1 WHERE id=$id");
	if ($result)
		$success_message = "$module_caption set homepage Successfully.";
	else
		$error_message = "Sorry! $module Could Not Be set homepage.";

/**
*******************
@@@ NO HOMEPAGE @@@
*******************
**/
} else if (($action == "nohome_$module" && !empty($id))) {

$result = $mysqli->query("UPDATE `$tbl_name` SET homepage=0 WHERE id=$id");
if ($result)
	$success_message = "$module_caption set nohomepage Successfully.";
else
	$error_message = "Sorry! $module Could Not Be set nohomepage.";

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
 ****************
@@@ UNPUBLISH @@@
*****************
**/
} else if (($action == "unpublish_$module" && !empty($id))) {

	if (unpublish($module_caption, $tbl_name, $id))
		$success_message = "$module_caption Un-Published Successfully.";
	else
		$error_message = "Sorry! $module Could Not Be Un-Published.";


/**
 *************
@@@ DELETE @@@
**************
**/
} else if (($action == "delete_$module" && !empty($id))) {

	//SUPERADMIN CAN DELETE ANY DATA
	if ($_SESSION[$project_pre]['type'] == 'superadmin') {

		$photo =	get_photo_name('photo', $id, $tbl_name);
		$result = $mysqli->query("DELETE FROM `$tbl_name` WHERE id=$id");

		// DELETE OLD PHOTO
		if (!empty($photo)) delete_photo($photo, $photo_upload_path);	

		//ADMIN CAN DELETE ONLY HIS/HER DATA
	} else {

		$photo =	get_photo_name('photo', $id, $tbl_name);
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
							<th width="40">Home</th>
							<th>Category</th>
							<th>Project Title</th>
							<th width="160">Image</th>
							<th width="40">views</th>
							<th width="40">amp</th>
							<th>last_visit</th>
							<th>Created</th>
							<!-- <th></th> -->
						</tr>
					</thead>
				</table>

			</div>
		</div>

		<?php include('admin_elements/copyright.php'); ?>
	</div>
</div>
<?php include('admin_elements/admin_footer.php'); ?>