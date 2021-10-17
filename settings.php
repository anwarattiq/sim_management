<?php
include('admin_elements/admin_header.php');
$module = 'settings';
$module_caption = 'Global Settings';
$tbl_name = $tbl_prefix . $module;
$photo_upload_path = '../uploads/' . $module . '/';
$allowed_file_size = $GLOBALS['PHOTO']['MAX_UPLOAD_SIZE']; //MB Bytes

$logo_amp_width = '150';
$logo_amp_height = '50';

$logo_header_width = '360';
$logo_header_height = '60';

$logo_footer_width = '360';
$logo_footer_height = '60';

$error_message = '';
$success_message = '';
#########################################
if (isset($_POST['minify_css'])) 				$minify_css = 1;
else $minify_css = 0;
if (isset($_POST['minify_js'])) 				$minify_js = 1;
else $minify_js = 0;

$create_thumbs = array(
	'apple-touch-icon1-' => '57',
	'apple-touch-icon2-' => '60',
	'apple-touch-icon3-' => '72',
	'apple-touch-icon4-' => '76',
	'apple-touch-icon5-' => '114',
	'apple-touch-icon6-' => '120',
	'apple-touch-icon7-' => '144',
	'apple-touch-icon8-' => '152',
	'apple-touch-icon9-' => '180',
	'favicon1-' => '16',
	'favicon2-' => '32',
	'favicon3-' => '96',
	'android-chrome-' => '192'
);

/**
 ************************************
 @@@ GET ALL VARIABLES ADD/UPDATE @@@
 ************************************
 **/
if ($action == "update_$module") {

	$site_title									= $mysqli->real_escape_string(stripslashes($_POST['site_title']));
	$site_url									= $mysqli->real_escape_string(stripslashes($_POST['site_url']));
	$meta_title									= $mysqli->real_escape_string(stripslashes($_POST['meta_title']));
	$meta_description							= $mysqli->real_escape_string(stripslashes($_POST['meta_description']));

	$whatsapp									= $mysqli->real_escape_string(stripslashes($_POST['whatsapp']));
	$footer_summary								= $mysqli->real_escape_string(stripslashes($_POST['footer_summary']));
	$contact_number								= $mysqli->real_escape_string(stripslashes($_POST['contact_number']));
	$email										= $mysqli->real_escape_string(stripslashes($_POST['email']));
	$address									= $mysqli->real_escape_string(stripslashes($_POST['address']));

	$listing_limit								= $mysqli->real_escape_string(stripslashes($_POST['listing_limit']));
	$sitemap_root								= $mysqli->real_escape_string(stripslashes($_POST['sitemap_root']));

	$gmb										= $mysqli->real_escape_string(stripslashes($_POST['gmb']));
	$facebook									= $mysqli->real_escape_string(stripslashes($_POST['facebook']));
	$twitter									= $mysqli->real_escape_string(stripslashes($_POST['twitter']));
	$pinterest									= $mysqli->real_escape_string(stripslashes($_POST['pinterest']));
	$linkedin									= $mysqli->real_escape_string(stripslashes($_POST['linkedin']));
	$medium										= $mysqli->real_escape_string(stripslashes($_POST['medium']));
	$quora										= $mysqli->real_escape_string(stripslashes($_POST['quora']));
	$instagram									= $mysqli->real_escape_string(stripslashes($_POST['instagram']));
	$kobo										= $mysqli->real_escape_string(stripslashes($_POST['kobo']));
} else {
	$site_title									= '';
	$site_url									= '';
	$meta_title									= '';
	$meta_description							= '';

	$whatsapp									= '';
	$footer_summary								= '';
	$contact_number								= '';
	$email										= '';
	$address									= '';

	$listing_limit								= '';
	$sitemap_root								= '';

	$gmb										= '';
	$facebook									= '';
	$twitter									= '';
	$pinterest									= '';
	$linkedin									= '';
	$medium										= '';
	$quora										= '';
	$instagram									= '';
	$kobo										= '';
}


/**
 ***********************
 @@@ DELETE AMP LOGO @@@
 ***********************
 **/
if (isset($_REQUEST['delete_amp_logo']) && $_REQUEST['delete_amp_logo'] == 1) {

	$amp_logo = get_photo_name('amp_logo', 1, $tbl_name);

	if (!empty($amp_logo)) {
		delete_photo($amp_logo, $photo_upload_path);

		$rs = $mysqli->query("UPDATE `$tbl_name` SET amp_logo='' WHERE id=1");
	}
}

/**
 *******************
 @@@ DELETE LOGO @@@
 *******************
 **/
if (isset($_REQUEST['delete_logo']) && $_REQUEST['delete_logo'] == 1) {

	$logo = get_photo_name('logo', 1, $tbl_name);

	if (!empty($logo)) {
		delete_photo($logo, $photo_upload_path);

		$rs = $mysqli->query("UPDATE `$tbl_name` SET logo='' WHERE id=1");
	}
}

/**
 **************************
 @@@ DELETE FOOTER LOGO @@@
 **************************
 **/
if (isset($_REQUEST['delete_logo_footer']) && $_REQUEST['delete_logo_footer'] == 1) {

	$logo_footer = get_photo_name('logo_footer', 1, $tbl_name);

	if (!empty($logo_footer)) {
		delete_photo($logo_footer, $photo_upload_path);

		$rs = $mysqli->query("UPDATE `$tbl_name` SET logo_footer='' WHERE id=1");
	}
}


/**
 ***********************
 @@@ DELETE FAVICONS @@@
 ***********************
 **/
if (isset($_REQUEST['delete_favicons']) && $_REQUEST['delete_favicons'] == 1) {

	$favicons = get_photo_name('favicons', 1, $tbl_name);

	if (!empty($favicons)) {
		delete_photo($favicons, $photo_upload_path);

		$rs = $mysqli->query("UPDATE `$tbl_name` SET favicons='' WHERE id=1");

		// DELETE THUMBS FOR APPLE++
		if ($rs) {
			foreach ($create_thumbs as $index => $key) {
				$thumb_name = $index . $key . 'x' . $key . '.png';
				delete_photo($thumb_name, $photo_upload_path);
				// delete_photo($thumb_name, $photo_upload_path, '1');
			}
		}
	}
}

/**
 **************
 @@@ UPDATE @@@
 **************
 **/
if ($action == "update_$module") {

	if (empty($site_title)) {
		$error_message = 'Site Title is mandatory.';
	} else if (empty($site_url)) {
		$error_message = 'Site URL is mandatory.';
	} else if (empty($meta_title)) {
		$error_message = 'Meta title is mandatory.';
	} else if (empty($meta_description)) {
		$error_message = 'Meta description is mandatory.';
	} else if (empty($sitemap_root)) {
		$error_message = 'Sitemap root is mandatory.';
	} else if (!preg_match('/.xml/', $sitemap_root)) {
		$error_message = 'Please include .xml in Sitemap Root.';
	} else if (empty($listing_limit)) {
		$error_message = 'listing_limit is mandatory.';
	} else if ($listing_limit < 3) {
		$error_message = 'listing_limit cannot be less than 3.';
	} else {
		/////// UPLOAD AMP LOGO ////////
		$amp_logo = $_FILES["amp_logo"]["name"];
		if (!empty($amp_logo)) {
			$old_photo 				= get_photo_name('amp_logo', $id, $tbl_name);
			$renamed 				= full_rename($amp_logo, 'imranonline-amp_logo');
			$message 				= upload_photo_without_thumb('amp_logo', $renamed, $photo_upload_path, $allowed_file_size);
			if ($message) $error_message = $message;
			else 					$result = $mysqli->query("UPDATE `$tbl_name` SET amp_logo='$renamed' WHERE id=1");
		}

		/////// UPLOAD LOGO ////////
		$logo = $_FILES["logo"]["name"];
		if (!empty($logo)) {
			$old_photo 				= get_photo_name('logo', $id, $tbl_name);
			$renamed 				= full_rename($logo, 'imranonline-logo');
			$message 				= upload_photo_without_thumb('logo', $renamed, $photo_upload_path, $allowed_file_size);
			if ($message) $error_message = $message;
			else 					$result = $mysqli->query("UPDATE `$tbl_name` SET logo='$renamed' WHERE id=1");
		}

		/////// UPLOAD FOOTER LOGO ////////
		$logo_footer = $_FILES["logo_footer"]["name"];
		if (!empty($logo_footer)) {
			$old_photo 				= get_photo_name('logo_footer', $id, $tbl_name);
			$renamed 				= full_rename($logo_footer, 'imranonline-logo-footer');
			$message 				= upload_photo_without_thumb('logo_footer', $renamed, $photo_upload_path, $allowed_file_size);
			if ($message) $error_message = $message;
			else 					$result = $mysqli->query("UPDATE `$tbl_name` SET logo_footer='$renamed' WHERE id=1");
		}
		//////////////////////////////////////////////////

		/////// UPLOAD FAVICONS ////////
		$favicons = $_FILES["favicons"]["name"];
		if (!empty($favicons)) {
			$old_photo 				= get_photo_name('favicons', $id, $tbl_name);
			$renamed 				= full_rename($favicons, 'favicons');
			$message 				= upload_photo_without_thumb('favicons', $renamed, $photo_upload_path, $allowed_file_size);

			if ($message) {
				$error_message = $message;
			} else {
				$result = $mysqli->query("UPDATE `$tbl_name` SET favicons='$renamed' WHERE id=1");

				// <!-- <link rel="apple-touch-icon" sizes="57x57" href="/images/favicons/apple-touch-icon-57x57.png">
				// <link rel="apple-touch-icon" sizes="60x60" href="/images/favicons/apple-touch-icon-60x60.png">
				// <link rel="apple-touch-icon" sizes="72x72" href="/images/favicons/apple-touch-icon-72x72.png">
				// <link rel="apple-touch-icon" sizes="76x76" href="/images/favicons/apple-touch-icon-76x76.png">
				// <link rel="apple-touch-icon" sizes="114x114" href="/images/favicons/apple-touch-icon-114x114.png">
				// <link rel="apple-touch-icon" sizes="120x120" href="/images/favicons/apple-touch-icon-120x120.png">
				// <link rel="apple-touch-icon" sizes="144x144" href="/images/favicons/apple-touch-icon-144x144.png">
				// <link rel="apple-touch-icon" sizes="152x152" href="/images/favicons/apple-touch-icon-152x152.png">
				// <link rel="apple-touch-icon" sizes="180x180" href="/images/favicons/apple-touch-icon-180x180.png">
				// <link rel="icon" type="image/png" href="/images/favicons/favicon-32x32.png" sizes="32x32">
				// <link rel="icon" type="image/png" href="/images/favicons/android-chrome-192x192.png" sizes="192x192">
				// <link rel="icon" type="image/png" href="/images/favicons/favicon-96x96.png" sizes="96x96">
				// <link rel="icon" type="image/png" href="/images/favicons/favicon-16x16.png" sizes="16x16"> -->

				// $full_renamed_photo = renameFullPhoto($renamed_photo, 'apple-touch-icon-57x57');
				// resize_and_crop("".$photo_upload_path."".$renamed_photo."", "".$photo_upload_path."thumbs/".$full_renamed_photo."", '57', '57', $quality=75);
				// Warning: imagecreatefromjpeg(): gd-jpeg: JPEG library reports unrecoverable error: Not a JPEG file: starts with 0x89 0x50 in F:\xampp\htdocs\hai\config\images.php on line 115

				// CREATE THUMBS FOR APPLE DEVICES++
				foreach ($create_thumbs as $index => $key) {
					$thumb_name = $index . $key . 'x' . $key;
					$full_renamed = full_rename($renamed, $thumb_name);
					resize_and_crop("" . $photo_upload_path . "" . $renamed . "", "" . $photo_upload_path . "thumbs/" . $full_renamed . "", $key, $key, $quality = 100);
				}
			}
		}

		// *************************
		// IN CASE Sitemaps Renaming
		// *************************
		$old_sitemap_root 							= getTableAttr("sitemap_root", $tbl_prefix . 'settings', 1);		// http sitemap

		if ($old_sitemap_root != $sitemap_root) {

			// RENAME HTTP
			if (file_exists('../' . $old_sitemap_root))						rename("../" . $old_sitemap_root . "", "../" . $sitemap_root . "");
		}

		// CREATE MINIFIED CSS CODE
		if ($minify_css == 1) include_once('../assets/css/minify.php');

		// CREATE MINIFIED JS CODE
		if ($minify_js == 1) 	include_once('../assets/js/minify.php');
		//Update Query
		$update_row = $mysqli->query("
		UPDATE `$tbl_name` SET
			minify_css					= '" . $minify_css . "',
			minify_js					= '" . $minify_js . "',
			site_title					= '" . $site_title . "',
			site_url					= '" . $site_url . "',
			meta_title					= '" . $meta_title . "',
			meta_description			= '" . $meta_description . "',
			listing_limit				= '" . $listing_limit . "',

			whatsapp					= '" . $whatsapp . "',
			footer_summary				= '" . $footer_summary . "',
			contact_number				= '" . $contact_number . "',
			email						= '" . $email . "',
			address						= '" . $address . "',			
			
			sitemap_root				= '" . $sitemap_root . "',
			gmb							= '" . $gmb . "',
			facebook					= '" . $facebook . "',
			twitter						= '" . $twitter . "',
			pinterest					= '" . $pinterest . "',
			linkedin					= '" . $linkedin . "',
			medium						= '" . $medium . "',
			quora						= '" . $quora . "',
			instagram					= '" . $instagram . "',
			kobo						= '" . $kobo . "'
		WHERE id=1");
		if ($update_row) {
			$success_message = "$module_caption Updated Successfully.";
			fp__($tbl_name, 1);
			// header("Location:settings.php.php?success_message=$success_message");
		} else {
			$error_message = "Sorry ! $module_caption Could Not Be Updated.";
			//header("Location:$module.php?action=edit_$module&id=$id&error_message=$error_message");
		}
	}
}

/**
 ************
 @@@ EDIT @@@
 ************
 **/

$result_settings 	= $mysqli->query("SELECT * FROM `$tbl_name` WHERE id=1");
$row_settings		= $result_settings->fetch_array();

$site_title						= stripslashes($row_settings['site_title']);
$site_url						= stripslashes($row_settings['site_url']);

$minify_css						= stripslashes($row_settings['minify_css']);
$minify_js						= stripslashes($row_settings['minify_js']);
$meta_title						= stripslashes($row_settings['meta_title']);
$meta_description				= stripslashes($row_settings['meta_description']);

$whatsapp						= stripslashes($row_settings['whatsapp']);
$contact_number					= stripslashes($row_settings['contact_number']);
$footer_summary					= stripslashes($row_settings['footer_summary']);
$email							= stripslashes($row_settings['email']);
$address						= stripslashes($row_settings['address']);

$listing_limit					= stripslashes($row_settings['listing_limit']);
$sitemap_root					= stripslashes($row_settings['sitemap_root']);

$gmb							= stripslashes($row_settings['gmb']);
$facebook						= stripslashes($row_settings['facebook']);
$twitter						= stripslashes($row_settings['twitter']);
$pinterest						= stripslashes($row_settings['pinterest']);
$linkedin						= stripslashes($row_settings['linkedin']);
$medium							= stripslashes($row_settings['medium']);
$quora							= stripslashes($row_settings['quora']);
$instagram						= stripslashes($row_settings['instagram']);
$kobo							= stripslashes($row_settings['kobo']);

$amp_logo 						= get_photo_name('amp_logo', 1, $tbl_name);
$logo 							= get_photo_name('logo', 1, $tbl_name);
$logo_footer 					= get_photo_name('logo_footer', 1, $tbl_name);
$favicons						= get_photo_name('favicons', 1, $tbl_name);
///////////////////////////////////////////////////////////////////////////////
?>

<div class="content-wrapper">

	<?php include('admin_elements/breadcrumb.php'); ?>

	<!-- Content area -->
	<div class="content">

		<!-- Dashboard content -->
		<div class="row">
			<div class="col-lg-12">

				<!-- Quick stats boxes -->
				<form method="post" id="frm<?php echo $module; ?>" name="frm<?php echo $module; ?>" action="<?php echo $module; ?>.php" enctype="multipart/form-data">
					<input type="hidden" name="action" id="action" value="update_<?php echo $module; ?>" />

					<div class="row">

						<div class="col-md-4">
							<div class="panel panel-flat">
								<div class="panel-heading">

									<div class="row">
										<div class="col-md-4">
											<div class="col-md-12">
												<h5><?php echo $module_caption; ?> </h5>
											</div>
										</div>

										<div class="col-md-3">
											<label>Minify CSS</label>
											<input type="checkbox" name="minify_css" id="minify_css" data-on-color="success" data-size="small" <?php if ($minify_css == '1') { ?>checked="checked" <?php } ?> />
										</div>
										<div class="col-md-3">
											<label>Minify JS</label>
											<input type="checkbox" name="minify_js" id="minify_js" data-on-color="success" data-size="small" <?php if ($minify_js == '1') { ?>checked="checked" <?php } ?> />
										</div>
										<div class="col-md-2">
											<label>&nbsp;</label>
											<div class="text-left">
												<button type="submit" class="btn btn-info">Update</button>
											</div>
										</div>
									</div>

								</div>

								<div class="panel-body">

									<div class="form-group">
										<label><strong>Site URL</strong> <span class="f_req">*</span></label>
										<input name="site_url" id="site_url" value="<?php echo $site_url; ?>" class="form-control" type="text">
										<span class="help-block">keep it without ssl and with www: http://www.example.com</span>
									</div>

									<div class="form-group">
										<label><strong>Meta Title</strong> <span class="f_req">*</span></label>
										<textarea class="sepH_a form-control" rows="3" cols="1" id="meta_title" name="meta_title" onkeyup="javascript: char_count(this.id);"><?php echo $meta_title; ?></textarea>
										<span class="help-block"><strong><span id="span_meta_title"><?php echo strlen($meta_title); ?></span></strong> &nbsp; - &nbsp; google 30 - 65 characters</span>
									</div>

									<div class="form-group">
										<label><strong>Meta Description</strong> <span class="f_req">*</span></label>
										<textarea class="sepH_a form-control" rows="3" cols="1" id="meta_description" name="meta_description" onkeyup="javascript: char_count(this.id);"><?php echo $meta_description; ?></textarea>
										<span class="help-block"><strong><span id="span_meta_description"><?php echo strlen($meta_description); ?></span></strong> &nbsp; - &nbsp; google 160 characters</span>
									</div>

									<div class="form-group">
										<label><strong>Site Title</strong> <span class="f_req">*</span></label>
										<input name="site_title" id="site_title" value="<?php echo $site_title; ?>" class="form-control" type="text">
									</div>

									<div class="form-group">
										<label><strong>Listing Limit</strong> </label>
										<input name="listing_limit" id="listing_limit" value="<?php echo $listing_limit; ?>" class="form-control" type="text">
										<span class="help-block">default=3</span>
									</div>

									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<label><strong>Sitemap Root</strong> <span class="f_req">*</span></label>
												<input name="sitemap_root" id="sitemap_root" value="<?php echo $sitemap_root; ?>" class="form-control" type="text">
												<span class="help-block">default: sitemap_mast.xml</span>
											</div>
										</div>
									</div>

								</div>
							</div>

						</div>

						<div class="col-md-4">
							<div class="panel panel-flat">
								<div class="panel-heading">

									<div class="row">
										<div class="col-md-7">
											<div class="col-md-12">
												<h5>Footer / Contact Information</h5>
											</div>
										</div>
									</div>

								</div>
								<div class="panel-body">
									<div class="alert alert-info">
										<button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
										Use <span class="text-bold">#</span> to leave/hide the values on front-end.
									</div>

									<div class="form-group">
										<label><strong>WhatsApp</strong> </label>
										<input name="whatsapp" id="whatsapp" value="<?php echo $whatsapp; ?>" class="form-control" type="text">
										<span class="help-block">format: https://api.whatsapp.com/send?phone=971505817573&text=Hey%20Imran%20%F0%9F%91%8B%0aCan%20you%20assist%20me%20please%3f</span>
									</div>

									<div class="form-group">
										<label><strong>Footer Summary</strong> </label>
										<textarea class="sepH_a form-control" rows="3" cols="1" id="footer_summary" name="footer_summary" onkeyup="javascript: char_count(this.id);"><?php echo $footer_summary; ?></textarea>
									</div>

									<div>
										<hr />
									</div>
									<div class="alert alert-info">
										<button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
										Use <span class="text-bold">Contact info</span> is used for both Footer & Contact Page.
									</div>

									<div class="form-group">
										<label><strong>Contact Number</strong> </label>
										<input name="contact_number" id="contact_number" value="<?php echo $contact_number; ?>" class="form-control" type="text">
										<span class="help-block">format: +971 50 123 4567</span>
									</div>

									<div class="form-group">
										<label><strong>Email</strong> </label>
										<input name="email" id="email" value="<?php echo $email; ?>" class="form-control" type="text">
									</div>

									<div class="form-group">
										<label><strong>Address</strong> </label>
										<input name="address" id="address" value="<?php echo $address; ?>" class="form-control" type="text">
									</div>

									<div>
										<hr />
									</div>

									<div class="row">
										<div class="col-md-7">
											<div class="col-md-12">
												<h5>Social Media</h5>
											</div>
										</div>
									</div>

									<div class="form-group">
										<label><strong>GMB</strong></label>
										<input name="gmb" id="gmb" value="<?php echo $gmb; ?>" class="form-control" type="text">
									</div>

									<div class="form-group">
										<label><strong>Facebook</strong></label>
										<input name="facebook" id="facebook" value="<?php echo $facebook; ?>" class="form-control" type="text">
									</div>

									<div class="form-group">
										<label><strong>Twitter</strong></label>
										<input name="twitter" id="twitter" value="<?php echo $twitter; ?>" class="form-control" type="text">
									</div>

									<div class="form-group">
										<label><strong>Pinterest</strong></label>
										<input name="pinterest" id="pinterest" value="<?php echo $pinterest; ?>" class="form-control" type="text">
									</div>

									<div class="form-group">
										<label><strong>Linkedin</strong></label>
										<input name="linkedin" id="linkedin" value="<?php echo $linkedin; ?>" class="form-control" type="text">
									</div>

									<div class="form-group">
										<label><strong>Medium</strong></label>
										<input name="medium" id="medium" value="<?php echo $medium; ?>" class="form-control" type="text">
									</div>

									<div class="form-group">
										<label><strong>Quora</strong></label>
										<input name="quora" id="quora" value="<?php echo $quora; ?>" class="form-control" type="text">
									</div>

									<div class="form-group">
										<label><strong>Instagram</strong></label>
										<input name="instagram" id="instagram" value="<?php echo $instagram; ?>" class="form-control" type="text">
									</div>

									<div class="form-group">
										<label><strong>Kobo</strong></label>
										<input name="kobo" id="kobo" value="<?php echo $kobo; ?>" class="form-control" type="text">
									</div>

								</div>

							</div>
						</div>


						<div class="col-md-4">
							<div class="panel panel-flat">
								<div class="panel-heading">

									<div class="row">
										<div class="col-md-7">
											<div class="col-md-12">
												<h5>All Logos / Favico</h5>
											</div>
										</div>
									</div>

								</div>
								<div class="panel-body">

									<div class="form-group">
										<label><strong>AMP Logo</strong> [<?php echo $logo_amp_width; ?>px x <?php echo $logo_amp_width; ?>]</label>

										<?php if (!empty($amp_logo)) { ?>
											<div class="form-group">
												<a href="<?php echo $photo_upload_path . $amp_logo; ?>" target="_blank">
													<img src="<?php echo $photo_upload_path . $amp_logo; ?>" width="<?php echo $logo_amp_width; ?>" />
												</a>
												<p><a href="<?php echo $module; ?>.php?delete_amp_logo=1">
														<button type="button" class="btn btn-default btn-sm" name="delete_amp_logo" id="delete_amp_logo">Delete</button></a>
												</p>
											</div>
										<?php } ?>

										<input type="file" name="amp_logo" id="amp_logo" class="file-styled">
										<span class="help-block">PNG format strictly recommended.</span>
									</div>

									<hr />
									<div class="form-group">
										<label><strong>Logo Header</strong> [<?php echo $logo_header_width; ?>px x <?php echo $logo_header_height; ?>]</label>

										<?php if (!empty($logo)) { ?>
											<div class="form-group">
												<a href="<?php echo $photo_upload_path . $logo; ?>" target="_blank">
													<img src="<?php echo $photo_upload_path . $logo; ?>" width="<?php echo $logo_header_width; ?>" />
												</a>
												<p><a href="<?php echo $module; ?>.php?delete_logo=1">
														<button type="button" class="btn btn-default btn-sm" name="delete_logo" id="delete_logo">Delete</button></a>
												</p>
											</div>
										<?php } ?>

										<input type="file" name="logo" id="logo" class="file-styled">
										<span class="help-block">PNG format strictly recommended.</span>
									</div>

									<hr />
									<div class="form-group">
										<label><strong>Logo Footer</strong> [<?php echo $logo_footer_width; ?>px x <?php echo $logo_footer_width; ?>]</label>

										<?php if (!empty($logo_footer)) { ?>
											<div class="form-group">
												<a href="<?php echo $photo_upload_path . $logo; ?>" target="_blank">
													<img src="<?php echo $photo_upload_path . $logo_footer; ?>" width="<?php echo $logo_footer_width; ?>" />
												</a>
												<p><a href="<?php echo $module; ?>.php?delete_logo_footer=1">
														<button type="button" class="btn btn-default btn-sm" name="delete_logo_footer" id="delete_logo_footer">Delete</button></a>
												</p>
											</div>
										<?php } ?>

										<input type="file" name="logo_footer" id="logo_footer" class="file-styled">
										<span class="help-block">PNG format strictly recommended. Max file size <?php echo $GLOBALS['PHOTO']['MAX_UPLOAD_SIZE']; ?>Mb</span>
									</div>



									<hr />
									<div class="form-group">
										<label><strong>Favico</strong> [square size 200x200px]</label>

										<?php if (!empty($favicons)) { ?>
											<div class="form-group">
												<a href="<?php echo $photo_upload_path . $logo; ?>" target="_blank">
													<img src="<?php echo $photo_upload_path . $favicons; ?>" alt="" width="200" height="200" />
												</a>
												<p><a href="<?php echo $module; ?>.php?delete_favicons=1">
														<button type="button" class="btn btn-default btn-sm" name="delete_favicons" id="delete_favicons">Delete</button></a>
												</p>
											</div>
										<?php } ?>

										<input type="file" name="favicons" id="favicons" class="file-styled">
										<span class="help-block">PNG format strictly recommended. While uploading will create logos for apple devices automatically.</span>
									</div>



									<div class="row">
										<?php
										foreach ($create_thumbs as $index => $key) {
											$thumb_name = $index . $key . 'x' . $key . '.png';

											if (file_exists($photo_upload_path . '/thumbs/' . $thumb_name)) {
										?>
												<div class="col-md-12">
													<div class="form-group">
														<label><?php echo $thumb_name; ?></label><br />
														<a href="<?php echo $photo_upload_path . '/thumbs/' . $thumb_name; ?>" target="_blank">
															<img src="<?php echo $photo_upload_path . '/thumbs/' . $thumb_name; ?>" alt="<?php echo $thumb_name; ?>" width="<?php echo $key; ?>" height="<?php echo $key; ?>" />
														</a><br />
													</div>
												</div>
										<?php
											} // end if
										} // foreach
										?>
									</div>


								</div>

							</div>
						</div>


					</div>
				</form>



			</div>


		</div>
	</div>
	<!-- /dashboard content -->

	<?php include('admin_elements/copyright.php'); ?>

</div>
<!-- /content area -->

</div>

<?php include('admin_elements/admin_footer.php'); ?>