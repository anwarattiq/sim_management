<?php
header("Expires: Sat, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: post-check=0, pre-check=0", false);
session_cache_limiter("must-revalidate");
ob_start();
session_start();
include('config/globals.php');
include('config/database.php');

$module = 'admins';
$module_caption = 'Admin';
$tbl_name = $tbl_prefix . $module;
$message = '';
$ip_address = $_SERVER['REMOTE_ADDR'];

if (isset($_SESSION[$project_pre]['username']) && !empty($_SESSION[$project_pre]['username'])) {
	header('location:index.php');
}
//$login_time = date("Y-m-d h:i:s", strtotime("+240 minutes", strtotime(date("Y-m-d H:i:s"))));
#########################################

if (isset($_POST['action']) 	&& !empty($_POST['action'])) {
	$action 	= $mysqli->real_escape_string($_POST['action']);
} else {
	$action = '';
}
if (isset($_POST['username']) && !empty($_POST['username'])) {
	$username = $mysqli->real_escape_string($_POST['username']);
} else {
	$username = '';
}
if (isset($_POST['password']) && !empty($_POST['password'])) {
	$password = $mysqli->real_escape_string($_POST['password']);
} else {
	$password = '';
}
// if (isset($_POST['login_token']) && !empty($_POST['login_token'])) 	{ $login_token = $mysqli->real_escape_string($_POST['login_token']); } 	else { $login_token = ''; }

if ($action == 'login' && !empty($username) && !empty($password)) {

	//CHECK IF FAILED 3 Times
	$admin_id = getIDFromUsername($username, $tbl_name);
	$result = $mysqli->query("SELECT id FROM `" . $tbl_prefix . "admin_blocks` WHERE admin_id = '$admin_id' ");
	$num_rows = $result->num_rows;

	if ($result->num_rows >= 300) {
		$message = 'Your account has been blocked due to maximum 3 invalid attempts. Please contact your administrator.';
		$mysqli->query("INSERT INTO `" . $tbl_prefix . "admin_blocks` (admin_id, ip) VALUES ('$admin_id', '$ip_address')");


		//CHECK IF NOT BLOCKED
	} else {

		//CHECK IF NOT ACTIVE
		$result = $mysqli->query("SELECT * FROM `$tbl_name` WHERE username='$username' AND password='" . md5($password) . "' AND active='0' LIMIT 1");
		$row = $result->fetch_array();
		if (!empty($row['id'])) {
			$message = 'Your account is not Active. Please contact your administrator.';

			//CHECK IF ACTIVE
		} else {
			//echo "SELECT * FROM `$tbl_name` WHERE username='$username' AND password='".md5($password)."' AND active='1' LIMIT 1";
			$result = $mysqli->query("SELECT * FROM `$tbl_name` WHERE username='$username' AND password='" . md5($password) . "' AND active='1' LIMIT 1");
			$row = $result->fetch_array();
			$email	= $row['email'];
			$mobile	= $row['mobile'];

			//DB TOKEN
			// if ( !empty($mobile)) {
			//    $security_code = substr($mobile, -4, 4);
			//    $token = md5($email.$security_code.'crm');
			// }

			if (!empty($row['id'])) { //&& $token == $login_token
				session_start();
				$_SESSION[$project_pre]['admin_id']		= $row['id'];
				$_SESSION[$project_pre]['type']			 	= $row['type'];
				$_SESSION[$project_pre]['full_name']	= $row['full_name'];
				$_SESSION[$project_pre]['username']	 	= $row['username'];
				$_SESSION[$project_pre]['email']		 	= $row['email'];
                $_SESSION[$project_pre]['company_id']		 	= $row['company_id'];

				$mysqli->query("INSERT INTO `" . $tbl_prefix . "admin_logs` (admin_id, ip) VALUES ('" . $row['id'] . "', '$ip_address')");
				$_SESSION[$project_pre]['login_time']	= timezone(date("Y-m-d H:i:s")); // - e
				if($row['type']=='admin'){
				    header('location:sims_request.php');
				    
				}else	header('location:index.php');
			} else {
				$mysqli->query("INSERT INTO `" . $tbl_prefix . "admin_blocks` (admin_id, ip) VALUES ('$admin_id', '$ip_address')");
				$message = 'Invalid Username / Password. Please contact your administrator.';
			}
		} //active

	}
}
///////////////////////////////////////////////////////////////////
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Admin Panel - Login Page</title>
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
	<script type="text/javascript" src="<?php echo $admin_base_url; ?>/assets/js/plugins/loaders/pace.min.js"></script>
	<script type="text/javascript" src="<?php echo $admin_base_url; ?>/assets/js/core/libraries/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo $admin_base_url; ?>/assets/js/core/libraries/bootstrap.min.js"></script>
	<script type="text/javascript" src="<?php echo $admin_base_url; ?>/assets/js/plugins/loaders/blockui.min.js"></script>
	<!-- /core JS files -->

	<!-- Theme JS files -->
	<script type="text/javascript" src="<?php echo $admin_base_url; ?>/assets/js/plugins/forms/validation/validate.min.js"></script>
	<script type="text/javascript" src="<?php echo $admin_base_url; ?>/assets/js/plugins/forms/styling/uniform.min.js"></script>

	<script type="text/javascript" src="<?php echo $admin_base_url; ?>/assets/js/core/app.js"></script>
	<!-- /theme JS files -->

</head>

<body class="login-container login-cover">

	<div class="page-container">
		<div class="page-content">
			<div class="content-wrapper">
				<div class="content pb-20">

					<form id="form_login" action="login.php" class="form-validate" method="post">
						<input type="hidden" name="action" id="action" value="login" />

						<div class="panel panel-body login-form">
							<div class="text-center">
								<div class="icon-object border-slate-300 text-slate-300"><i class="icon-reading"></i></div>
								<h5 class="content-group">Login to your account
									<?php if (!empty($message)) { ?><small class="display-block"> <?php echo $message; ?> </small><?php } ?>
								</h5>
							</div>

							<div class="form-group has-feedback has-feedback-left">
								<input type="text" class="form-control" placeholder="Username" name="username" id="username" value="<?php echo $username; ?>" />
								<div class="form-control-feedback">
									<i class="icon-user text-muted"></i>
								</div>
							</div>

							<div class="form-group has-feedback has-feedback-left">
								<input type="password" class="form-control" placeholder="Password" name="password" id="password" />
								<div class="form-control-feedback">
									<i class="icon-lock2 text-muted"></i>
								</div>
							</div>

							<!-- <div class="form-group has-feedback has-feedback-left">
								<input type="text" class="form-control" placeholder="Token" name="login_token" id="login_token" value="<?php echo $login_token; ?>" />
								<div class="form-control-feedback">
									<i class="icon-lock2 text-muted"></i>
								</div>
							</div> -->

							<!-- <div class="form-group login-options">
								<div class="row"> -->
							<!-- <div class="col-sm-6">
										<a href="token.php">Token?</a>
									</div> -->

							<!-- <div class="col-sm-6 text-right">
										<a href="#">Forgot password?</a>
									</div> -->
							<!-- </div>
							</div> -->

							<div class="form-group">
								<button type="submit" class="btn bg-blue btn-block">Login <i class="icon-arrow-right14 position-right"></i></button>
							</div>

						</div>
					</form>

				</div>
			</div>
		</div>
	</div>

</body>

</html>
<?php
$mysqli->close();
ob_flush();
?>