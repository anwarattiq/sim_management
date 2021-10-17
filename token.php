<?php
	session_start();
	include ('../config/globals.php');
	include ('../config/database.php');
	// include ('../config/site.php');

	$module = 'admins';
	$module_caption = 'Admin';
	$tbl_name = $tbl_prefix.$module;
	$message='';
	$ip_address = $_SERVER['REMOTE_ADDR'];

	if (isset($_SESSION[$project_pre]['username']) && !empty($_SESSION[$project_pre]['username'])) { header('location:index.php'); }
	//$login_time = date("Y-m-d h:i:s", strtotime("+240 minutes", strtotime(date("Y-m-d H:i:s"))));
	#########################################
  $token = '';
	if (isset($_POST['action'])        && !empty($_POST['action']))        { $action= $mysqli->real_escape_string($_POST['action']); } else { $action=''; }
	if (isset($_POST['email'])         && !empty($_POST['email'])) 	       { $email = $mysqli->real_escape_string($_POST['email']); }	else { $email = ''; }
	if (isset($_POST['security_code'])  && !empty($_POST['security_code'])) 	{ $security_code = $mysqli->real_escape_string($_POST['security_code']); } 	  else { $security_code = ''; }

	if ($action == 'login'){

      if (empty($email)){
        $message = 'Email is mandatory.';

      } else if (empty($security_code)){
        $message = 'Security Code is mandatory.';

      } else {

				//CHECK IF VALID EMAIL / SECURITY CODE
				$result = $mysqli->query("SELECT * FROM `$tbl_name` WHERE email='".$email."' LIMIT 1");
				$row = $result->fetch_array();
          $id = $row['id'];
          $mobile = $row['mobile'];

        //IF NOT VALID
  			if ( !empty($mobile) && ($security_code == substr($mobile, -4, 4)) ) {
              $token = md5($email.$security_code.'crm');
				} else {
          $message = 'Invalid Email Address / Secutiy Code. Please contact your administrator.';
				}

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
	<title>Admin Panel - Token</title>
	<meta name="robots" content="noindex">

	<!-- Global stylesheets -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
	<link href="assets/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
	<link href="assets/css/bootstrap.css" rel="stylesheet" type="text/css">
	<link href="assets/css/core.css" rel="stylesheet" type="text/css">
	<link href="assets/css/components.css" rel="stylesheet" type="text/css">
	<link href="assets/css/colors.css" rel="stylesheet" type="text/css">
	<!-- /global stylesheets -->

	<!-- Core JS files -->
	<script type="text/javascript" src="assets/js/plugins/loaders/pace.min.js"></script>
	<script type="text/javascript" src="assets/js/core/libraries/jquery.min.js"></script>
	<script type="text/javascript" src="assets/js/core/libraries/bootstrap.min.js"></script>
	<script type="text/javascript" src="assets/js/plugins/loaders/blockui.min.js"></script>
	<!-- /core JS files -->

	<!-- Theme JS files -->
	<script type="text/javascript" src="assets/js/plugins/forms/validation/validate.min.js"></script>
	<script type="text/javascript" src="assets/js/plugins/forms/styling/uniform.min.js"></script>

	<script type="text/javascript" src="assets/js/core/app.js"></script>
	<!-- /theme JS files -->

</head>

<body class="login-container login-cover">

	<div class="page-container">
		<div class="page-content">
			<div class="content-wrapper">
				<div class="content pb-20">

					<form id="form_login" action="token.php"  method="post">
					<input type="hidden" name="action" id="action" value="login" />

					<div class="panel panel-body login-form">
						<div class="text-center">
							<div class="icon-object border-warning text-warning"><i class="icon-spinner11"></i></div>
							<h5 class="content-group">Token
								<?php if (!empty($message)){ ?><small class="display-block"> <?php echo $message;?> </small><?php }?>
							</h5>
						</div>

						<div class="form-group has-feedback">
							<input class="form-control" type="email" id="email" name="email" placeholder="Your email" value="<?php echo $email;?>" />
							<div class="form-control-feedback">
								<i class="icon-mail5 text-muted"></i>
							</div>
						</div>

						<div class="form-group has-feedback">
							<input class="form-control" type="text" id="security_code" name="security_code" placeholder="Your Security Code" value="<?php echo $security_code;?>" />
							<div class="form-control-feedback">
								<i class="icon-lock2 text-muted"></i>
							</div>
						</div>

						<?php if (!empty($token)){ ?>
		        <div class="form-group has-feedback">
			        <div class="input-group" style="color: green;"><h5><?php echo $token;?></h5></div>
			      </div>

						<div class="form-group login-options">
							<div class="row">
								<div class="col-sm-12 text-center">
									<h5><a href="login.php">Back to Login Page</a></h5>
								</div>
							</div>
						</div>
		      	<?php }?>

						<button type="submit" class="btn bg-blue btn-block">Generate <i class="icon-arrow-right14 position-right"></i></button>

					</div>
					</form>

				</div>
			</div>
		</div>
	</div>

</body>
</html>
