<?php
	include('admin_elements/admin_header.php');
	$module = 'email_history';
	$module_caption = 'Email history';
	$tbl_name = $tbl_prefix.$module;
	$error_message = ''; $success_message = '';
	#########################################

	/**
  **************************************
           @@@ DELETE @@@
  **************************************
  **/
  if ( ($action=="delete_$module" && !empty($id))){

    //SUPERADMIN CAN DELETE ANY DATA
    if ($_SESSION[$project_pre]['type']=='superadmin'){
      $result = $mysqli->query("DELETE FROM `$tbl_name` WHERE id=$id");

    //ADMIN CAN DELETE ONLY HIS/HER DATA
    } else {
      $result = $mysqli->query("DELETE FROM `$tbl_name` WHERE id=$id AND created_by='".$_SESSION[$project_pre]['admin_id']."'");
    }

    if($result){
      $success_message = "$module_caption Deleted Successfully.";
      header("Location:listing_$module.php?page=$page&success_message=$success_message");
    } else {
      $error_message = "Sorry! $module Could Not Be Deleted.";
    }
  }
  ///////////////////////////////////////////////////////////////////////////////
	include('admin_elements/errors.php');
	?>
		<div id="contentwrapper">
			<div class="main_content">
				<?php include('admin_elements/breadcrumb.php');?>
				<?php include('admin_elements/errors.php');?>

					<table id="grid-<?php echo $module;?>" class="table">
							<thead>
							<tr>
								<th>Manage</th>
								<th>Campaign</th>
								<th>Provider</th>
								<th>Email</th>
	              <th>Created</th>
							</tr>
						</thead>
					</table>

			</div>
			<!--END CONTENT-->
		</div>
	</div>
	<?php include('admin_elements/sidebar.php');?>
	<?php include('admin_elements/admin_footer.php');?>
