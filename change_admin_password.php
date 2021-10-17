<?php 
	include('admin_elements/admin_header.php');
	include('admin_elements/only_superadmin.php');
	$module = 'admins';
	$module_caption = 'Admin';
	$tbl_name = $tbl_prefix.$module;
	$error_message = ''; $success_message = '';
	include('classes/class.phpmailer.php');
	#########################################		
	if (isset($_POST['admin_id']) && !empty($_POST['admin_id'])){ $admin_id = $_POST['admin_id']; } else { $admin_id = ''; }	
	
	/**
	**************************************
   @@@ GET ALL VARIABLES ADD/UPDATE @@@
	**************************************
	**/	
	if ($action=='change_password'){
		$new_password = $_POST['new_password'];
		$confirm_new_password = $_POST['confirm_new_password'];

	} else {		
		$current_password = '';
		$new_password = '';
	}

	/**
	**************************************
					 	   @@@ ADD @@@
	**************************************
	**/
	if ($action=='change_password'){

			if (strlen($new_password)<6){
				$error_message = 'New Password should be at least 6 characters long.';
				
			} else if ($new_password != $confirm_new_password){
				$error_message = 'New Password and Confirm New Password Mismatch!';				
			
			} else {

					//Update Query
					$update_row = $mysqli->query("UPDATE `$tbl_name` SET password='".md5($new_password)."' WHERE id='".$_POST['admin_id']."'");
					
						################################## START SEND EMAIL #################################
						$to = $_SESSION['email'];
						$subject = 'New Password Information';
						$body = 'Your new password is: "'.$new_password.'"';
						try {
							$mail=new PHPMailer(true); $mail->IsSMTP(); $mail->SMTPAuth=true;
							//**********************************************
							$mail->Port=$GLOBALS['SITE']['SMTP_PORT']; 
							$mail->Host=$GLOBALS['SITE']['SMTP_HOST']; 
							$mail->Username=$GLOBALS['SITE']['SMTP_USERNAME']; 
							$mail->Password=$GLOBALS['SITE']['SMTP_PASSWORD'];
							$mail->IsSendmail();
							$reply_to = $GLOBALS['SITE']['EMAIL_REPLYTO']; 
							$reply_to_name = $GLOBALS['SITE']['EMAIL_REPLYTONAME'];
							$from = $GLOBALS['SITE']['EMAIL_FROMEMAIL']; 
							$from_name = $GLOBALS['SITE']['EMAIL_FROMNAME'];
							//**********************************************
							$mail->AddAddress($to); $mail->Subject=$subject; $mail->AltBody=""; $mail->WordWrap=80; $mail->From = $from; $mail->FromName = $from_name; $mail->MsgHTML($body); $mail->IsHTML(true); $mail->Send();
							//echo 'Mail Sentzzz';
						} catch (phpmailerException $e) { echo $e->errorMessage(); }//end try catch
						################################## END SEND EMAIL ###################################
						$success_message = 'Congratulations! Your password is changed successfully.';
			}
	}
	///////////////////////////////////////////////////////////////////////////////
?>
  <div id="contentwrapper">
    <div class="main_content">      
  		<?php include('admin_elements/breadcrumb.php');?>
			<?php include('admin_elements/errors.php');?>
      
			<div class="row">
      	<div class="col-sm-6 col-md-6">
        <h3 class="heading">Change Password - For Username -> <?php echo getUsernameFromID($_REQUEST["admin_id"], $tbl_prefix.'admins');?></h3>
        
        <form method="post" id="customForm" action="change_admin_password.php">
        <input type="hidden" name="action" id="action" value="change_password" />
        <input type="hidden" name="admin_id" id="admin_id" value="<?php echo $admin_id;?>" />
        
          
          <div class="formSep">
            <div class="row">
              <div class="col-sm-6 col-md-6">
                <label>New Password <span class="f_req">*</span></label>
                <input name="new_password" id="new_password" type="password" class="form-control" />
                <span class="help-block">Please enter new password</span>
              </div>
            </div>
          </div>
          <div class="formSep">
            <div class="row">
              <div class="col-sm-6 col-md-6">
                <label>ReType New Password<span class="f_req">*</span></label>
                <input name="confirm_new_password" id="confirm_new_password" type="password" class="form-control" />
              </div>
            </div>
          </div>
          <div class="form-actions">
            <button class="btn btn-default" type="submit">Save changes</button>
          </div>
        </form>
              
      	</div>
      </div>
        
    </div> 
    <!--END CONTENT-->
  </div>
</div>
<?php include('admin_elements/sidebar.php');?>  
<?php include('admin_elements/admin_footer.php');?>  