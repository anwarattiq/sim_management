<?php
include('admin_elements/admin_header.php');
$module = 'article_categories';
$module_caption = 'Article Category';
$tbl_name = $tbl_prefix . $module;
$photo_upload_path = '../uploads/' . $module . '/';
$allowed_file_size = $GLOBALS['PHOTO']['MAX_UPLOAD_SIZE']; //MB Bytes
$error_message = '';
$success_message = '';
#########################################

if (isset($_POST['publish'])) 				$publish = 1;
else $publish = 0;;

if (isset($_POST['parent_id'])) 		$parent_id = $mysqli->real_escape_string(stripslashes($_POST['parent_id']));
else $parent_id = 0;

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
 **************
	@@@ DELETE @@@
 **************
 **/
if (($action == "delete_$module" && !empty($id))) {

	$result = 0;
	//SUPERADMIN CAN DELETE ANY DATA
	if ($_SESSION[$project_pre]['type'] == 'superadmin') {

		$cascade   = $mysqli->query("SELECT id FROM `" . $GLOBALS['TBL']['PREFIX'] . "articles` WHERE category=$id");
		if ($cascade->num_rows > 0) {
			$error_message = "Category associated with rows in Articles Table. ";
		} else {
			$result = $mysqli->query("DELETE FROM `$tbl_name` WHERE id=$id");
		}

		//ADMIN CAN DELETE ONLY HIS/HER DATA
	} else {
		$cascade   = $mysqli->query("SELECT id FROM `" . $GLOBALS['TBL']['PREFIX'] . "articles` WHERE category=$id");
		if ($cascade->num_rows > 0) {
			$error_message = "Category associated with rows in Articles Table.";
		} else {
			$result = $mysqli->query("DELETE FROM `$tbl_name` WHERE id=$id AND created_by='" . $_SESSION[$project_pre]['admin_id'] . "'");
		}
	}


	if ($result) {
		$success_message = "$module_caption Deleted Successfully.";
		header("Location:$module.php?page=$page&success_message=$success_message");
	} else {
		$error_message .= "Sorry! $module Could Not Be Deleted.";
	}

	/**
	 **************
	@@@ UPDATE @@@
	 **************
	 **/
} else if ($action == "update_$module" && !empty($id)) {

	if (empty($category) || $category == 'Please select') {
		$error_message = 'Category is mandatory.';
	} else if (empty($slug)) {
		$error_message = 'Slug is mandatory.';
	} else {

		//////////////////////////////////////////////////
		$update_row = $mysqli->query("
								UPDATE `$tbl_name` SET
									parent_id		= '" . $parent_id . "',
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
		// } else if (empty($slug)) {
		// 	$error_message = 'Slug is mandatory.';
		// } else if (checkDuplicateRow($tbl_name, 'slug', $slug)) {
		// 	$error_message = 'Duplicate Slug. Please enter different.';
	} else {

		$slug 	= generate_article_slug($category);

		// echo "INSERT INTO `$tbl_name`(parent_id, category, slug, publish) VALUES ('" . $parent_id . "', '" . $category . "', '" . $slug . "', '" . $publish . "'); ";
		$insert_row = $mysqli->query("INSERT INTO `$tbl_name`(parent_id, category, slug, publish) VALUES ('" . $parent_id . "', '" . $category . "', '" . $slug . "', '" . $publish . "'); ");

		if ($insert_row) {
			$id = $mysqli->insert_id;
			$success_message = "$module_caption Saved Successfully.";
			header("Location:$module.php?success_message=$success_message");
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

	$parent_id							= stripslashes($row['parent_id']);
	$category							= stripslashes($row['category']);
	$slug								= stripslashes($row['slug']);
	$publish 							= stripslashes($row['publish']);
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

							</div>


						</div>
					</div>

				</div>


				<div class="col-md-6">
					<div class="panel panel-flat">
						<div class="panel-heading">

							<div class="panel-body">

								<div class="row">
									<div class="form-group">
										<?php echo show_article_categories($id); ?>
									</div>
								</div>

							</div>

						</div>
					</div>

					<!-- <div class="help-block">Select "Parent" while adding very first Menu.</div> -->

				</div>


			</div>
		</form>

		<?php include('admin_elements/copyright.php'); ?>
	</div>
</div>
<?php include('admin_elements/admin_footer.php'); ?>