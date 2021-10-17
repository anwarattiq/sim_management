<?php
header("Expires: Sat, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: post-check=0, pre-check=0", false);
session_cache_limiter("must-revalidate");
ob_start();
session_start();
header("Content-Type: text/html; charset=utf-8");
require('config/globals.php');
require('config/database.php');

include('config/images.php');
include('admin_elements/timeout.php');
include('admin_elements/security.php');
include('admin_elements/grab_vars.php');
#######################################

/**
 **************************************
	 @@@ STATISTICS
 **************************************
 **/
// $result_statistics = $mysqli->query("SELECT * FROM `".$GLOBALS['TBL']['PREFIX']."statistics` WHERE id=1");
// $row_statistics		 = $result_statistics->fetch_array();
////////////////////////////////////////////////////////
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Admin Panel - Dashboard</title>
	<meta name="robots" content="noindex">
	<link rel="shortcut icon" href="<?php echo $admin_base_url; ?>/favicon.ico" />

	<!-- Global stylesheets -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
	<link href="<?php echo $admin_base_url; ?>/assets/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
	<link href="<?php echo $admin_base_url; ?>/assets/css/bootstrap.css" rel="stylesheet" type="text/css">
	<link href="<?php echo $admin_base_url; ?>/assets/css/core.css" rel="stylesheet" type="text/css">
	<link href="<?php echo $admin_base_url; ?>/assets/css/components.css" rel="stylesheet" type="text/css">
	<link href="<?php echo $admin_base_url; ?>/assets/css/colors.css" rel="stylesheet" type="text/css">
	<!-- /global stylesheets -->

	<!-- Core JS files -->
	<script src="<?php echo $admin_base_url; ?>/assets/js/plugins/loaders/pace.min.js"></script>
	<script src="<?php echo $admin_base_url; ?>/assets/js/core/libraries/jquery.min.js"></script>
	<script src="<?php echo $admin_base_url; ?>/assets/js/core/libraries/bootstrap.min.js"></script>
	<script src="<?php echo $admin_base_url; ?>/assets/js/plugins/loaders/blockui.min.js"></script>
	<!-- /core JS files -->


	<!-- Theme JS files -->
	<script src="<?php echo $admin_base_url; ?>/assets/js/plugins/visualization/d3/d3.min.js"></script>
	<script src="<?php echo $admin_base_url; ?>/assets/js/plugins/visualization/d3/d3_tooltip.js"></script>
	<script src="<?php echo $admin_base_url; ?>/assets/js/plugins/forms/styling/switchery.min.js"></script>
	<script src="<?php echo $admin_base_url; ?>/assets/js/plugins/forms/styling/uniform.min.js"></script>
	<script src="<?php echo $admin_base_url; ?>/assets/js/plugins/forms/selects/bootstrap_multiselect.js"></script>
	<script src="<?php echo $admin_base_url; ?>/assets/js/plugins/ui/moment/moment.min.js"></script>
	<script src="<?php echo $admin_base_url; ?>/assets/js/plugins/pickers/daterangepicker.js"></script>
	<script src="<?php echo $admin_base_url; ?>/assets/js/core/app.js"></script>

	<?php if ($current_page == 'sitemaps.php') { ?>
		<link rel="stylesheet" href="<?php echo $admin_base_url; ?>/css/jqtree.css">
		<script src="<?php echo $admin_base_url; ?>/js/tree.jquery.js"></script>
	<?php } ?>

	<?php if ($current_page == 'email_reports.php') { ?>
		<script src="<?php echo $admin_base_url; ?>/assets/js/pages/dashboard.js"></script>
	<?php } ?>


	<?php if ($current_page != 'index.php') { ?>
		<script src="<?php echo $admin_base_url; ?>/assets/js/plugins/forms/selects/select2.min.js"></script>
		<script src="<?php echo $admin_base_url; ?>/assets/js/plugins/ui/fullcalendar/fullcalendar.min.js"></script>
		<script type="text/javascript" src="<?php echo $admin_base_url; ?>/assets/js/plugins/forms/selects/select2.min.js"></script>
		<script type="text/javascript" src="<?php echo $admin_base_url; ?>/assets/js/plugins/forms/styling/uniform.min.js"></script>
		<script type="text/javascript" src="<?php echo $admin_base_url; ?>/assets/js/pages/form_inputs.js"></script>
		<script src="<?php echo $admin_base_url; ?>/assets/js/plugins/forms/styling/uniform.min.js"></script>
		<script src="<?php echo $admin_base_url; ?>/assets/js/pages/form_layouts.js"></script>
		<script src="<?php echo $admin_base_url; ?>/assets/js/pages/extra_fullcalendar.js"></script>
	<?php } ?>
	<!-- /theme JS files -->

	<!-- <link href="<?php echo $admin_base_url; ?>/css/jquery-ui.css" rel="Stylesheet" type="text/css" /> -->
	<!-- <script src="<?php echo $admin_base_url; ?>/js/jquery-1.11.3.js"></script> -->
	<!-- <script src="https://code.jquery.com/jquery-1.12.4.js"></script> -->
	<script src="<?php echo $admin_base_url; ?>/js/jquery-ui.js"></script>

	<script src="<?php echo $admin_base_url; ?>/js/bootstrap-switch.min.js"></script>
	<script>
		$(document).ready(function() {
			$("[name='publish']").bootstrapSwitch();
			$("[name='main_menu']").bootstrapSwitch();
			$("[name='php_page']").bootstrapSwitch();
			$("[name='active']").bootstrapSwitch();
			$("[name='send_email']").bootstrapSwitch();
			$("[name='cache']").bootstrapSwitch();
			$("[name='minify_css']").bootstrapSwitch();
			$("[name='minify_js']").bootstrapSwitch();
			$("[name='manager']").bootstrapSwitch();

			$('input[id$=created]').datepicker({
				dateFormat: 'dd-mm-yy'
			});

			// $('#created').datepicker({
			// 	// startDate: '-3d',
			// 	format: 'dd-mm-yyyy',
			// 	startDate: '0d',
			// });

			$('#created').on('changeDate', function(ev) {
				$(this).datepicker('hide');
			});
		});
	</script>

	<script src="<?php echo $admin_base_url; ?>/js/ajax.js"></script>
	<script src="<?php echo $admin_base_url; ?>/js/site.js"></script>
	<?php include('internal_request.php'); ?>

	<?php include('admin_elements/tablesdata.php'); ?>
	<style>
		.page-title {
			padding: 20px 32px 15px 0 !important;
		}
	</style>



</head>

<body <?php if ($current_page == 'email_templates.php') { ?>class="sidebar-xs" <?php } ?>>

	<!-- Main navbar -->
	<div class="navbar navbar-inverse">
		<div class="navbar-header">
			<a class="navbar-brand" href="index.php"><img src="<?php echo $admin_base_url; ?>/assets/images/logo_light.png" alt=""></a>

			<ul class="nav navbar-nav visible-xs-block">
				<li><a data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-tree5"></i></a></li>
				<li><a class="sidebar-mobile-main-toggle"><i class="icon-paragraph-justify3"></i></a></li>
			</ul>
		</div>

		<div class="navbar-collapse collapse" id="navbar-mobile">
			<ul class="nav navbar-nav">
				<li><a class="sidebar-control sidebar-main-toggle hidden-xs"><i class="icon-paragraph-justify3"></i></a></li>
				<p class="navbar-text">
					<span class="label bg-success">ver 1.0</span>&nbsp;&nbsp;
				</p>
			</ul>

			<ul class="nav navbar-nav navbar-right">

				<li class="dropdown dropdown-user">
					<a class="dropdown-toggle" data-toggle="dropdown">
						<span><?php echo $_SESSION[$project_pre]['full_name']; ?></span>
						<i class="caret"></i>
					</a>

					<ul class="dropdown-menu dropdown-menu-right">
						<li><a href="profile.php"><i class="icon-user-plus"></i> Profile</a></li>
						<li><a href="change_password.php"><i class="icon-key"></i> Change Password</a></li>

						<?php if ($_SESSION[$project_pre]['type'] == 'superadmin') { ?>
							<li class="divider"></li>
							<li><a href="listing_admins.php"><i class="icon-users4"></i> Users</a></li>
							<li><a href="listing_admin_logs.php"><i class="icon-stack2"></i> Logs</a></li>
							<li><a href="listing_admin_blocks.php"><i class="icon-lock2"></i> Blocks</a></li>
						<?php } ?>

						<li class="divider"></li>
						<li><a href="logout.php"><i class="icon-switch2"></i> Logout</a></li>
					</ul>
				</li>
			</ul>
		</div>
	</div>
	<!-- /main navbar -->


	<!-- Page container -->
	<div class="page-container">

		<!-- Page content -->
		<div class="page-content">

			<?php include('admin_elements/sidebar.php'); ?>