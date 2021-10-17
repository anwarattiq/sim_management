<?php
include('admin_elements/admin_header.php');
$module = 'pages';
$module_caption = 'Page';
$tbl_name = $tbl_prefix . $module;

$photo_upload_path = '../uploads/' . $module . '/';
$allowed_file_size = $GLOBALS['PHOTO']['MAX_UPLOAD_SIZE']; //MB Bytes
$thumb_width = '200';
$thumb_height = '150';
$image_width = '500';
$image_height = '375';

$error_message = '';
$success_message = '';
#########################################
if (isset($_POST['publish'])) 							$publish = 1;
else $publish = 0;
if (isset($_POST['php_page'])) 							$php_page = 1;
else $php_page = 0;
if (isset($_POST['views']) && !empty($_POST['views'])) 		$views = $mysqli->real_escape_string(stripslashes($_POST['views']));
else $views = 0;
if (isset($_POST['bit']) && !empty($_POST['bit'])) 			$bit = $mysqli->real_escape_string(stripslashes($_POST['bit']));
else $bit = 0;


/**
 ************************************
    @@@ GET ALL VARIABLES ADD/UPDATE @@@
 ************************************
 **/
if ($action == "update_$module" || $action == "add_$module") {
	$slug        		 = $mysqli->real_escape_string(stripslashes($_POST['slug']));
	$menu_caption        = $mysqli->real_escape_string(stripslashes($_POST['menu_caption']));
	$page_description    = $mysqli->real_escape_string(stripslashes($_POST['page_description']));
	$slider_heading 	 = $mysqli->real_escape_string(stripslashes($_POST['slider_heading']));
	$slider_subheading   = $mysqli->real_escape_string(stripslashes($_POST['slider_subheading']));
	$meta_title 	     = $mysqli->real_escape_string(stripslashes($_POST['meta_title']));
	$meta_description 	 = $mysqli->real_escape_string(stripslashes($_POST['meta_description']));
	// $ordering		 = $mysqli->real_escape_string(stripslashes($_POST['ordering']));

} else {
	$slug     			= '';
	$menu_caption     	= '';
	$page_description 	= '';
	$slider_heading   	= '';
	$slider_subheading	= '';
	$meta_title       	= '';
	$meta_description 	= '';
	// $ordering	   	= '';
}


/**
 *************************
	@@@ DELETE PHOTO ONLY @@@
 *************************
 **/
if (isset($_REQUEST['delete_photo']) && $_REQUEST['delete_photo'] == 1 && !empty($id)) {
	$photo =	get_photo_name('photo', $id, $tbl_name);

	// DELETE OLD PHOTO
	if (!empty($photo)) delete_photo($photo, $photo_upload_path);

	$result = $mysqli->query("UPDATE `$tbl_name` SET photo='' WHERE id=$id");
	$success_message = 'Image Deleted Successfully.';
}


/**
 **************
	@@@ UPDATE @@@
 **************
 **/
if ($action == "update_$module" && !empty($id)) {

	if (empty($menu_caption)) {
		$error_message = 'Menu Caption is mandatory.';
	} else if (empty($slug)) {
		$error_message = 'Slug is mandatory.';
	} else if ($php_page == 1 && (!file_exists('../' . $slug . '.php'))) {
		$error_message = '<strong>' . $slug . '.php</strong> page does not exist. Please create <strong>' . $slug . '.php</strong> page or make <strong>' . $slug . '</strong> as content page.';
	} else if (empty($meta_title)) {
		$error_message = 'Meta title is mandatory.';
	} else if (empty($meta_description)) {
		$error_message = 'Meta description is mandatory.';
	} else {
		// $slug = slugify($menu_caption);
		// slug 					    = '".$slug."',

		/////// UPLOAD PHOTO ////////
		$old_photo =	get_photo_name('photo', $id, $tbl_name);
		$photo = $_FILES["photo"]["name"];

		if (!empty($photo)) {
			$renamed 			= renamePhoto($photo, $complete = 0);
			$message 			= upload_photo_with_thumb('photo', $renamed, $photo_upload_path, $allowed_file_size, $old_photo, $thumb_width, $thumb_height);
			if ($message)		$error_message = $message;
			else				$result = $mysqli->query("UPDATE `$tbl_name` SET photo='$renamed' WHERE id=$id");
		} //endif
		///////////////////////////////////////////////
		//Update Query
		$update_row = $mysqli->query("
						UPDATE `$tbl_name` SET
							slug 	    				= '" . $slug . "',
							menu_caption 	    = '" . $menu_caption . "',
							page_description 	= '" . $page_description . "',
							slider_heading 	  = '" . $slider_heading . "',
							slider_subheading = '" . $slider_subheading . "',
							meta_title 	      = '" . $meta_title . "',
							meta_description 	= '" . $meta_description . "',
							php_page					= '" . $php_page . "',
							publish		 		    = '" . $publish . "'
						WHERE id=$id");
		if ($update_row) {
			$success_message = "$module_caption Updated Successfully.";
			fp__($tbl_name, $id);

			/////// UPLOAD PHOTO ////////
			$photo = $_FILES["photo"]["name"];
			if (!empty($photo)) {
				$renamed 		= renamePhoto($photo, $complete = 0);
				$message 		= upload_photo_with_thumb('photo', $renamed, $photo_upload_path, $allowed_file_size, '', $thumb_width, $thumb_height);
				if ($message)	$error_message = $message;
				else			$result = $mysqli->query("UPDATE `$tbl_name` SET photo='$renamed' WHERE id=$id");
			} //endif

			// header("Location:listing_$module.php?success_message=$success_message");
		} else {
			$error_message = "Sorry ! $module_caption Could Not Be Updated.";
			//header("Location:$module.php?action=edit_$module&id=$id&error_message=$error_message");
		}
	}

	/**
	 ***********
	@@@ ADD @@@
	 ***********
	 **/
} else if ($action == "add_$module") {

	if (empty($menu_caption)) {
		$error_message = 'Menu Caption is mandatory.';
	} else if (empty($slug)) {
		$error_message = 'Slug is mandatory.';
	} else if ($php_page == 1 && (!file_exists('../' . $slug . '.php'))) {
		$error_message = '<strong>' . $slug . '.php</strong> page does not exist. Please create <strong>' . $slug . '.php</strong> page or make <strong>' . $slug . '</strong> as content page.';
	} else if (empty($meta_title)) {
		$error_message = 'Meta title is mandatory.';
	} else if (empty($meta_description)) {
		$error_message = 'Meta description is mandatory.';
	} else {

		$slug = slugify($slug);

		//Insert Query
		$insert_row = $mysqli->query("INSERT INTO `$tbl_name`(slug, menu_caption, page_description, slider_heading, slider_subheading, meta_title, meta_description, php_page, publish) VALUES ('" . $slug . "', '" . $menu_caption . "', '" . $page_description . "', '" . $slider_heading . "', '" . $slider_subheading . "', '" . $meta_title . "', '" . $meta_description . "', '" . $php_page . "', '" . $publish . "')");
		if ($insert_row) {
			$success_message = "$module_caption Saved Successfully.";
			fp__($tbl_name, $mysqli->insert_id);
			// header("Location:listing_$module.php?success_message=$success_message");
			//////////////////////////////////////////////////
		} else {
			$error_message = "Sorry ! $module_caption Could Not Be Saved.";
			//header("Location:$module.php?error_message=$error_message");
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

	$slug 				= stripslashes($row['slug']);
	$menu_caption	    = stripslashes($row['menu_caption']);
	$page_description	= stripslashes($row['page_description']);
	$slider_heading	  	= stripslashes($row['slider_heading']);
	$slider_subheading	= stripslashes($row['slider_subheading']);
	$meta_title	      	= stripslashes($row['meta_title']);
	$meta_description	= stripslashes($row['meta_description']);
	$php_page			= stripslashes($row['php_page']);
	$publish			= stripslashes($row['publish']);
}

$photo = get_photo_name('photo', $id, $tbl_name);
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
										<h5><?php echo $module_caption; ?> Details <?php if (!empty($slug)) { ?><a href="<?php echo $base_url; ?>/page/<?php echo $slug; ?>" target="_blank">&#128065;</a><?php } ?></h5>
									</div>
								</div>

								<div class="col-md-9 text-right">
									<div class="col-md-3"></div>
									<div class="col-md-5">
										<label>php_page</label>
										<input type="checkbox" name="php_page" id="php_page" data-on-color="success" data-size="small" <?php if ($php_page == '1') { ?>checked="checked" <?php } ?> />
									</div>
									<div class="col-md-2">
										<input type="checkbox" name="publish" id="publish" data-on-color="success" data-size="small" <?php if ($publish == '1') { ?>checked="checked" <?php } ?> />
									</div>
									<div class="col-md-2">
										<button type="submit" class="btn btn-info"><?php if (($action == "edit_$module" || $action == "update_$module") && !empty($id)) { ?>Update<?php } else { ?>Save<?php } ?> </button>
									</div>
								</div>
							</div>

							<div class="panel-body">

								<div class="form-group">
									<label class="col-lg-3 control-label"><strong>Slug</strong> <span class="f_req">*</span></label>
									<div class="col-lg-9">
										<input name="slug" id="slug" value="<?php echo $slug; ?>" class="form-control" type="text" onkeyup="javascript: char_count(this.id);" maxlength="160">
										<span class="help-block"><strong><span id="span_slug"><?php echo strlen($slug); ?></span></strong> &nbsp; - &nbsp; Unique 200 chars (manual)</span>
									</div>
								</div>

								<div class="form-group">
									<label class="col-lg-3 control-label"><strong>Menu Caption</strong> <span class="f_req">*</span></label>
									<div class="col-lg-9">
										<input name="menu_caption" id="menu_caption" value="<?php echo $menu_caption; ?>" class="form-control" type="text">
									</div>
								</div>

								<div class="form-group">
									<label class="col-lg-3 control-label"><strong>Meta Title</strong> <span class="f_req">*</span></label>
									<div class="col-lg-9">
										<input name="meta_title" id="meta_title" value="<?php echo $meta_title; ?>" class="form-control" type="text" onkeyup="javascript: char_count(this.id);">
										<span class="help-block"><strong><span id="span_meta_title"><?php echo strlen($meta_title); ?></span></strong> &nbsp; - &nbsp; Max 65 characters</span>
									</div>
								</div>

								<div class="form-group">
									<label class="col-lg-3 control-label"><strong>Meta Description</strong> <span class="f_req">*</span></label>
									<div class="col-lg-9">
										<textarea class="sepH_a form-control" rows="3" cols="1" id="meta_description" name="meta_description" onkeyup="javascript: char_count(this.id);"><?php echo $meta_description; ?></textarea>
										<span class="help-block"><strong><span id="span_meta_description"><?php echo strlen($meta_description); ?></span></strong> &nbsp; - &nbsp; Max 160 characters</span>
									</div>
								</div>

								<hr />

								<div class="form-group">
									<label class="col-lg-3 control-label"><strong>Slider Heading</strong></label>
									<div class="col-lg-9">
										<input name="slider_heading" id="slider_heading" value="<?php echo $slider_heading; ?>" class="form-control" type="text">
									</div>
								</div>

								<div class="form-group">
									<label class="col-lg-3 control-label"><strong>Slider Subheading</strong></label>
									<div class="col-lg-9">
										<input name="slider_subheading" id="slider_subheading" value="<?php echo $slider_subheading; ?>" class="form-control" type="text">
									</div>
								</div>

								<div class="form-group">
									<label class="col-lg-3 control-label"><strong>Photo</strong><br />[<?php echo $image_width; ?>px x <?php echo $image_height; ?>px]</label>
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
													<img src="<?php echo $photo_upload_path . $photo ?>" alt="" width="<?php echo $thumb_width; ?>" />
												</a>
												<p><a href="<?php echo $module; ?>.php?action=<?php echo $action; ?>&id=<?php echo $id; ?>&delete_photo=1';">
														<button type="button" class="btn btn-default btn-sm" name="delete_photo" id="delete_photo">Delete Photo</button></a>
												</p>
											</div>
										<?php } ?>
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

								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label><strong>Page Description</strong></label>
											<textarea class="sepH_a form-control" rows="3" cols="1" id="page_description" name="page_description"><?php echo $page_description; ?></textarea>
										</div>
									</div>
								</div>

							</div>
						</div>
					</div>
				</div>

			</div>
		</form>

		<div class="alert alert-info alert-styled-left alert-arrow-left alert-component">
			<ul>
				<li><strong>php_page:1</strong> .php page is mandatory with same slug.</li>
		</div>

		<?php include('admin_elements/copyright.php'); ?>
	</div>
</div>
<?php include('admin_elements/admin_footer.php'); ?>