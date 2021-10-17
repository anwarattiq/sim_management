<?php
include('admin_elements/admin_header.php');
$module = 'articles';
$module_caption = 'Article';
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

if (isset($_POST['publish'])) 								$publish = 1;																else $publish = 0;
if (isset($_POST['views']) && !empty($_POST['views'])) 		$views = $mysqli->real_escape_string(stripslashes($_POST['views']));		else $views = 0;
if (isset($_POST['bit']) && !empty($_POST['bit'])) 			$bit = $mysqli->real_escape_string(stripslashes($_POST['bit']));			else $bit = 0;

// Default Today's Date if Created Date is not entered
$created				= date("d-m-Y", strtotime("today", time()));

/**
 ************************************
	@@@ GET ALL VARIABLES ADD/UPDATE @@@
 ************************************
 **/
if ($action == "update_$module" || $action == "add_$module") {
	$slug						= $mysqli->real_escape_string(stripslashes($_POST['slug']));
	$category					= $_POST['category'];
	$article_title				= $mysqli->real_escape_string(stripslashes($_POST['article_title']));
	$article_description		= $mysqli->real_escape_string(stripslashes($_POST['article_description']));
	$meta_title					= $mysqli->real_escape_string(stripslashes($_POST['meta_title']));
	$meta_description			= $mysqli->real_escape_string(stripslashes($_POST['meta_description']));
	$created					= $mysqli->real_escape_string(stripslashes($_POST['created']));
} else {
	$slug						= '';
	$category					= '';
	$article_title				= '';
	$article_description		= '';
	$meta_title					= '';
	$meta_description			= '';
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
	} else if (empty($article_title)) {
		$error_message = 'Article Title is mandatory.';
	} else if (empty($article_description)) {
		$error_message = 'Article Description is mandatory.';
	} else if (empty($slug)) {
		$error_message = 'Slug is mandatory.';
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
				$slug = generate_article_slug($article_title);
				$mysqli->query("UPDATE `$tbl_name` SET slug	= '" . $slug . "' WHERE id=$id");

				// UPDATED UNIQUE ENTERED SLUG
			} else {
				$mysqli->query("UPDATE `$tbl_name` SET slug	= '" . $slug . "' WHERE id=$id");
			}
		}

		/////// UPLOAD PHOTO ////////
		$photo = $_FILES["photo"]["name"];

		if (!empty($photo)) {
			$old_photo 			=	get_photo_name('photo', $id, $tbl_name);
			$renamed 			= full_rename($photo, $slug);
			$message 			= upload_photo_with_thumb('photo', $renamed, $photo_upload_path, $allowed_file_size, $old_photo, $thumb_width, $thumb_height);
			if ($message)		$error_message = $message;
			else				$result = $mysqli->query("UPDATE `$tbl_name` SET photo='$renamed' WHERE id=$id");
		} //endif

		//////////////////////////////////////////////////

		// PROCESS MULTIPLE CATEGORIES
		$categories_ids = '';
		if ($category) {

			$categories = $_POST['category'];
			if (!empty($categories)) {

				foreach ($categories as $category_id) {
					$categories_ids .= $category_id . ',';
				} //foreach

				$categories_ids = substr($categories_ids, 0, -1);
			}
		}

		$created		= processDateDtoY($created);
		$created		= $created . ' 00:00:00';;

		$update_row = $mysqli->query("
				UPDATE `$tbl_name` SET
					category						= '" . $categories_ids . "',
					slug							= '" . $slug . "',
					article_title					= '" . $article_title . "',
					article_description				= '" . $article_description . "',
					meta_title						= '" . $meta_title . "',
					meta_description				= '" . $meta_description . "',
					created							= '" . $created . "',
					publish 						= '" . $publish . "'
				WHERE id=$id");

		if ($update_row) {
			$success_message = "$module_caption Updated Successfully.";
			fp__($tbl_name, $id);
			
			// UPDATE COMMENTS
			if (isset($_POST['comments'])){
			
				$comments = $_POST['comments'];
				if (!empty($comments)) {
					foreach ($comments as $k => $v) {
						$comment_id 	= $k;
						$comment_body = $mysqli->real_escape_string(stripslashes($v));
						$comment_body = trim(preg_replace('/\s+/', ' ', $comment_body));
						// echo "UPDATE `".$GLOBALS['TBL']['PREFIX']."reddit_comments` SET body = '".$comment_body."' WHERE id=$comment_id"; echo '<br /><br />';
						$mysqli->query("UPDATE `" . $GLOBALS['TBL']['PREFIX'] . "reddit_comments` SET body = '" . $comment_body . "' WHERE id=$comment_id");
					} //foreach
				} //endif
			}
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

	// if (empty($category) || $category == 'Please select') {
	// 	$error_message = 'Category is mandatory.';
	if (empty($article_title)) {
		$error_message = 'Article Title is mandatory.';
	} else if (empty($article_description)) {
		$error_message = 'Article Description is mandatory.';
	} else {

		$slug 	= generate_article_slug($article_title);
		$bit 	= generateRandomString(9);

		// PROCESS MULTIPLE CATEGORIES
		$categories_ids = '';
		if ($category) {

			$categories = $_POST['category'];
			if (!empty($categories)) {

				foreach ($categories as $category_id) {
					$categories_ids .= $category_id . ',';
				} //foreach

				$categories_ids = substr($categories_ids, 0, -1);
			}
		}

		$created		= processDateDtoY($created);
		$created		= $created . ' 00:00:00';

		echo $created;

		$insert_row = $mysqli->query("INSERT INTO `$tbl_name`(category, slug, bit, article_title, article_description, meta_title, meta_description, created, publish) VALUES ('" . $categories_ids . "', '" . $slug . "', '" . $bit . "', '" . $article_title . "', '" . $article_description . "', '" . $meta_title . "', '" . $meta_description . "', '" . $created . "', '" . $publish . "'); ");

		if ($insert_row) {
			$id = $mysqli->insert_id;
			fp__($tbl_name, $id);
			$success_message = "$module_caption Saved Successfully.";
			/////// UPLOAD PHOTO ////////
			$photo = $_FILES["photo"]["name"];
			if (!empty($photo)) {
				$renamed 		= full_rename($photo, $slug);
				$message 		= upload_photo_with_thumb('photo', $renamed, $photo_upload_path, $allowed_file_size, '', $thumb_width, $thumb_height);
				if ($message)	$error_message = $message;
				else			$result = $mysqli->query("UPDATE `$tbl_name` SET photo='$renamed' WHERE id=$id");
			} //endif
			header("Location:listing_$module.php?success_message=$success_message");
			//////////////////////////////////////////////////
		} else {
			$error_message .= "Sorry ! $module_caption Could Not Be Saved.";
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

	$category					= stripslashes($row['category']);
	$category_arr				= explode(',', $category);
	$slug						= stripslashes($row['slug']);
	$article_title				= stripslashes($row['article_title']);
	$article_description		= stripslashes($row['article_description']);
	$permalink					= stripslashes($row['permalink']);
	$views						= stripslashes($row['views']);
	$meta_title					= stripslashes($row['meta_title']);
	$meta_description			= stripslashes($row['meta_description']);
	$created 					= stripslashes($row['created']);
	$created					= substr($created, 0, -9); // . ' 00:00:00';
	$created	 				= processDateYtoD($created);

	$publish 					= stripslashes($row['publish']);
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
										<h5><?php echo $module_caption; ?> Details <?php if (!empty($slug)) { ?><a href="<?php echo $base_url; ?>/article/<?php echo $slug; ?>" target="_blank">&#128065;</a><?php } ?></h5>
									</div>
								</div>

								<div class="col-md-9 text-right">
									<div class="col-md-2">&nbsp;</div>
									<div class="col-md-3">
										<!-- <input value="<?php echo $views; ?> &#128065;" class="form-control" type="text"> -->
										<input name="created" id="created" value="<?php echo $created; ?>" class="form-control" type="text">
									</div>
									<div class="col-md-3">
										<input type="checkbox" name="publish" id="publish" data-on-color="success" data-size="small" <?php if ($publish == '1') { ?>checked="checked" <?php } ?> />
									</div>
									<div class="col-md-2">
										<a href="listing_<?php echo $module; ?>.php"><button type="button" class="btn btn-default"> Listing </button></a>
									</div>
									<div class="col-md-2">
										<button type="submit" class="btn btn-info"><?php if (($action == "edit_$module" || $action == "update_$module") && !empty($id)) { ?>Update<?php } else { ?>Save<?php } ?> </button>
									</div>

								</div>

							</div>

							<div class="panel-body">

								<div class="form-group">
									<label class="col-lg-3 control-label"><strong>Title</strong> <span class="f_req">*</span></label>
									<div class="col-lg-9">
										<input name="article_title" id="article_title" value="<?php echo $article_title; ?>" class="form-control" type="text">
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
									<label class="col-lg-3 control-label"><strong>Category</strong></label>
									<div class="col-lg-9">
										<select class="form-control" name="category[]" id="category[]" multiple="multiple" style="height:400px;">
											<?php if (($action == "add_$module") || empty($id)) { ?>
											<option value='1' selected>Uncategorized</option>
											<?php }?>
											<?php
											$result = $mysqli->query("SELECT * FROM `" . $tbl_prefix . "article_categories` WHERE publish=1 ORDER BY category");
											while ($rows = $result->fetch_array()) {
											?>
												<option value="<?php echo $rows['id']; ?>" <?php if ($action == "edit_$module" && in_array($rows['id'], $category_arr)) { ?>selected <?php } else if ($rows['id'] == $category) { ?>selected <?php } ?>>
													<?php echo $rows['category']; ?>
												</option>
											<?php } ?>
										</select>
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
												<p><a href="<?php echo $module; ?>.php?action=<?php echo $action; ?>&id=<?php echo $id; ?>&delete_photo=1;">
													<button type="button" class="btn btn-default btn-sm" name="delete_photo" id="delete_photo">Delete Photo</button></a>
												</p>
											</div>
										<?php } ?>
									</div>
								</div>

								<div class="form-group">
									<label class="col-lg-3 control-label"><strong>Meta Title</strong> </label>
									<div class="col-lg-9">
										<input name="meta_title" id="meta_title" value="<?php echo $meta_title; ?>" class="form-control" type="text" onkeyup="javascript: char_count(this.id);" maxlength="65">
										<span class="help-block"><strong><span id="span_meta_title"><?php echo strlen($meta_title); ?></span></strong> &nbsp; - &nbsp; Max 65 characters</span>
									</div>
								</div>

								<div class="form-group">
									<label class="col-lg-3 control-label"><strong>Meta Description</strong> </label>
									<div class="col-lg-9">
										<input name="meta_description" id="meta_description" value="<?php echo $meta_description; ?>" class="form-control" type="text" onkeyup="javascript: char_count(this.id);" maxlength="160">
										<span class="help-block"><strong><span id="span_meta_description"><?php echo strlen($meta_description); ?></span></strong> &nbsp; - &nbsp; Max 160 characters</span>
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

								<div class="form-group">
									<label><strong>Article Description</strong> <span class="f_req">*</span></label>
									<textarea class="sepH_a form-control" rows="3" cols="1" id="article_description" name="article_description"><?php echo $article_description; ?></textarea>
								</div>

								<?php
								// BANNED WORDS
								$banned_words 		= array();
								$result_ban 			= $mysqli->query("SELECT * FROM `" . $tbl_prefix . "banned_words`");
								while ($rows_ban 	= $result_ban->fetch_array()) {
									$word_id 			= $rows_ban['id'];
									$banned_word 	= $rows_ban['banned_word'];
									$banned_word 	= trim($banned_word);
									array_push($banned_words, $banned_word);
								}

								if (!empty($permalink)) {
									$result = $mysqli->query("SELECT * FROM `" . $tbl_prefix . "reddit_comments` WHERE permalink LIKE '" . $permalink . "%' AND body!=''");

									echo '<h3>' . $result->num_rows . ' Comments <a href="sync_reddit_comments.php?action=sync_reddit_comments&post_id=' . $id . '&my_subreddit=' . $category . '&permalink=' . $permalink . '" target="_blank"><i class="splashy-refresh"></i></a></h3> ';

									while ($rows = $result->fetch_array()) {
										$comment_id = $rows['id'];

										$body = $rows['body'];
										$body = $body;

										$body = html_entity_decode($body);
										$body = cleanInput($body);
										$body = str_ireplace('<div class="md">', '', $body);
										$body = str_ireplace('</div>', '', $body);

										$highlight = '';
										$body_words = explode(' ', $body);
										foreach ($body_words as $word) {
											if (in_array($word, $banned_words)) {
												echo '<span style="color:red;">' . $word . '</span>';
												$highlight = "red";
											}
										} //foreach

										$autogrow = 2;
										if (strlen($body) > 120) $autogrow = 3;
										if (strlen($body) > 200) $autogrow = 4;
										if (strlen($body) > 300) $autogrow = 5;
								?>
										<div class="form-group">
											<textarea style="border-color:<?php echo $highlight; ?>;" class="sepH_a form-control" rows="<?php echo $autogrow; ?>" id="comments[<?php echo $comment_id; ?>]" name="comments[<?php echo $comment_id; ?>]"><?php echo $body; ?></textarea>
										</div>
								<?php
									} //while

								} //endif
								?>



							</div>
						</div>
					</div>
				</div>

			</div>
		</form>

		<?php include('admin_elements/copyright.php'); ?>
	</div>
</div>
<?php include('admin_elements/admin_footer.php'); ?>