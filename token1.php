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
              $token = md5($email.$security_code.'haiuae.com');
				} else {
          $message = 'Invalid Email Address / Secutiy Code. Please contact your administrator.';
				}

      }

	}
	///////////////////////////////////////////////////////////////////
?>
<!DOCTYPE html>
<html lang="en" class="login_page">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Admin Panel</title>
<!-- Bootstrap framework -->
<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css" />
<!-- theme color-->
<link rel="stylesheet" href="css/blue.css" />
<!-- tooltip -->
<link rel="stylesheet" href="lib/qtip2/jquery.qtip.min.css" />
<!-- main styles -->
<link rel="stylesheet" href="css/style.css" />
<!-- favicon -->
<link rel="shortcut icon" href="favicon.ico" />
<!--[if lt IE 9]>
<script src="js/ie/html5.js"></script>
<script src="js/ie/respond.min.js"></script>
<![endif]-->
</head>
<body>
<div class="login_box">
  <form id="form_login" action="token.php"  class="formular" method="post">
  <input type="hidden" name="action" id="action" value="login" />
    <div class="top_b">Smiley ;)</div>
    <?php if (!empty($message)){ ?>
      <div class="alert alert-info alert-login"> <?php echo $message;?> </div>
    <?php }?>

    <div class="cnt_b">
      <div class="form-group">
        <div class="input-group"> <span class="input-group-addon input-sm"><i class="glyphicon glyphicon-user"></i></span>
          <input class="form-control input-sm" type="text" id="email" name="email" placeholder="Email" value="<?php echo $email;?>" />
        </div>
      </div>

      <div class="form-group">
        <div class="input-group"> <span class="input-group-addon input-sm"><i class="glyphicon glyphicon-lock"></i></span>
          <input class="form-control input-sm" type="text" id="security_code" name="security_code" placeholder="Security Code" value="<?php echo $security_code;?>" />
        </div>
      </div>

      <?php if (!empty($token)){ ?>
        <div class="form-group">
        <div class="input-group"> <span class="input-group-addon input-sm"><i class="splashy-sheild glyphicon-lock"></i></span>
          <input class="form-control input-sm" type="text" value="<?php echo $token;?>" />
        </div>
      </div>
      <?php }?>

    </div>
    <div class="btm_b clearfix">
      <button class="btn btn-default btn-sm pull-right" type="submit">Generate My Token</button>
  </form>
</div>

</body>
</html>
