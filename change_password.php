<?php
	include('admin_elements/admin_header.php');
	$module = 'admins';
	$module_caption = 'Admin';
	$tbl_name = $tbl_prefix.$module;
	$error_message = ''; $success_message = '';

	// include('classes/class.phpmailer.php');
	#########################################
	if (isset($_POST['action'])){ $action = $_POST['action']; } else { $action = ''; }

	/**
	**************************************
   @@@ GET ALL VARIABLES ADD/UPDATE @@@
	**************************************
	**/
	if ($action=='change_password'){
		$current_password = $_POST['current_password'];
		$new_password = $_POST['new_password'];
		$confirm_new_password = $_POST['confirm_new_password'];

	} else {
		$current_password = '';
		$new_password = '';
		$confirm_new_password = '';
	}

	/**
	**************************************
					 	   @@@ ADD @@@
	**************************************
	**/
	if ($action=='change_password'){

		$result = $mysqli->query("SELECT id, password FROM `$tbl_name` WHERE username='".$_SESSION[$project_pre]['username']."' AND password='".md5($current_password)."' LIMIT 1"); _err_($mysqli);
		$row = $result->fetch_array();

			if ( empty($row['id']) ) {
				$error_message = 'Incorrect Current Password!';

			} else if (strlen($new_password)<6){
				$error_message = 'New Password should be at least 6 characters long.';

			} else if ($new_password != $confirm_new_password){
				$error_message = 'New Password and Confirm New Password Mismatch!';

			} else {

					//Update Query
					$update_row = $mysqli->query("UPDATE `$tbl_name` SET password='".md5($new_password)."' WHERE id='".$_SESSION[$project_pre]['admin_id']."'");

						################################## START SEND EMAIL #################################
						// $to = $_SESSION['email'];
						// $subject = 'New Password Information';
						// $body = 'Your new password is: "'.$new_password.'"';
						// try {
						// 	$mail=new PHPMailer(true); $mail->IsSMTP(); $mail->SMTPAuth=true;
						// 	//**********************************************
						// 	$mail->Port=$GLOBALS['SITE']['SMTP_PORT'];
						// 	$mail->Host=$GLOBALS['SITE']['SMTP_HOST'];
						// 	$mail->Username=$GLOBALS['SITE']['SMTP_USERNAME'];
						// 	$mail->Password=$GLOBALS['SITE']['SMTP_PASSWORD'];
						// 	$mail->IsSendmail();
						// 	$reply_to = $GLOBALS['SITE']['EMAIL_REPLYTO'];
						// 	$reply_to_name = $GLOBALS['SITE']['EMAIL_REPLYTONAME'];
						// 	$from = $GLOBALS['SITE']['EMAIL_FROMEMAIL'];
						// 	$from_name = $GLOBALS['SITE']['EMAIL_FROMNAME'];
						// 	//**********************************************
						// 	$mail->AddAddress($to); $mail->Subject=$subject; $mail->AltBody=""; $mail->WordWrap=80; $mail->From = $from; $mail->FromName = $from_name; $mail->MsgHTML($body); $mail->IsHTML(true); $mail->Send();
						// 	//echo 'Mail Sentzzz';
						// } catch (phpmailerException $e) { echo $e->errorMessage(); }//end try catch
						################################## END SEND EMAIL ###################################
						$success_message = 'Congratulations! Your password is changed successfully.';
						header("Location:logout.php");
			}
	}
	///////////////////////////////////////////////////////////////////////////////
?>
<div class="content-wrapper">
	<div class="content">

		<?php include('admin_elements/breadcrumb.php');?>

		<div class="row">
			<div class="col-md-6">

				<form method="post" id="frm<?php echo $module;?>" name="frm<?php echo $module;?>" action="change_password.php" class="form-horizontal">
        <input type="hidden" name="action" id="action" value="change_password" />
					<div class="panel panel-flat">
						<div class="panel-heading"><h5 class="panel-title">Change Password - <?php echo $_SESSION[$project_pre]['username'];?></h5></div>

						<div class="panel-body">

							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label>Current Password:</label>
										<input type="password" class="form-control" name="current_password" id="current_password" />
									</div>

									<div class="form-group">
										<label>New Password:</label>
										<input type="password" class="form-control" name="new_password" id="new_password" />
										<span class="help-block">At least 6 characters</span>
									</div>

									<div class="form-group">
										<label>ReType New Password:</label>
										<input type="password" class="form-control" name="confirm_new_password" id="confirm_new_password" />
									</div>
								</div>
							</div>

							<div class="text-right">
								<button class="btn btn-primary" type="submit">Update</button>
							</div>
						</div>
					</div>
				</form>

			</div>
		</div>

		<?php include('admin_elements/copyright.php');?>
	</div>
</div>
<?php include('admin_elements/admin_footer.php');?>
