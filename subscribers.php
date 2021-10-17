<?php
	include('admin_elements/admin_header.php');
	$module = 'subscribers';
	$module_caption = 'Subscriber';
	$tbl_name = $tbl_prefix.$module;
	$error_message = ''; $success_message = '';
	#########################################

  $list_id = '';
  if (isset($_REQUEST['list_id']) && !empty($_REQUEST['list_id']))
    $list_id	= $mysqli->real_escape_string(stripslashes($_REQUEST['list_id']));

	/**
	**************************************
   @@@ GET ALL VARIABLES ADD/UPDATE @@@
	**************************************
	**/
	if ($action=="update_$module" || $action=="add_$module"){
    $email 				    = $mysqli->real_escape_string(stripslashes($_POST['email']));
		$first_name				= $mysqli->real_escape_string(stripslashes($_REQUEST['first_name']));
		$last_name 				= $mysqli->real_escape_string(stripslashes($_REQUEST['last_name']));

	} else {
    $email 				= '';
		$first_name  	= '';
		$last_name   	= '';
	}

	/**
	**************************************
							@@@ UPDATE @@@
	**************************************
	**/
	if ($action=="update_$module" && !empty($id)){

			if (empty($email)){
				$error_message = 'Email is mandatory.';

      } else if (empty($list_id)){
          $error_message = 'List is mandatory.';

			} else {
							//////////////////////////////////////////////////
							//Update Query
							$update_row = $mysqli->query("
															UPDATE `$tbl_name` SET
                                email 					= '".$email."',
                                list_id     		= '".$list_id."',
																first_name      = '".$first_name."',
																last_name       = '".$last_name."'
															WHERE id='".$id."' ");
							if($update_row){
								$success_message = "$module_caption Updated Successfully.";
								fp__($tbl_name, $id);
								header("Location:listing_subscribers.php?list_id=$list_id&success_message=$success_message");
							} else {
								$error_message = "Sorry ! $module_caption Could Not Be Updated.";
								//header("Location:$module.php?action=edit_$module&id=$id&error_message=$error_message");
							}
			}
	/**
	**************************************
					 	   @@@ EDIT @@@
	**************************************
	**/

	} else if ( ($action=="edit_$module" && !empty($id))){

			$result = $mysqli->query("SELECT * FROM `$tbl_name` WHERE id=$id");
			$row = $result->fetch_array();

        $email						= stripslashes($row['email']);
				$list_id 					= stripslashes($row['list_id']);
				$first_name				= stripslashes($row['first_name']);
				$last_name 				= stripslashes($row['last_name']);


	/**
	**************************************
					 	   @@@ ADD @@@
	**************************************
	**/
	} else if ($action=="add_$module"){

			if (empty($email)){
				$error_message = 'Email is mandatory.';

      } else if (empty($list_id)){
          $error_message = 'List is mandatory.';

			} else {

							//Insert Query
							$insert_row = $mysqli->query("INSERT INTO `$tbl_name`(list_id, first_name, last_name, email) VALUES ('".$list_id."', '".$first_name."', '".$last_name."', '".$email."')");
							if($insert_row){
								$success_message = "$module_caption Saved Successfully.";
								$id = $mysqli->insert_id;
								fp__($tbl_name, $id);
								header("Location:listing_subscribers.php?list_id=$list_id&success_message=$success_message");
								//////////////////////////////////////////////////
							} else {
								$error_message = "Sorry ! $module_caption Could Not Be Saved.";
								//header("Location:$module.php?error_message=$error_message");
							}
			}

	}

  ///////////////////////////////////////////////////////////////////////////////
?>
<div class="content-wrapper">
	<div class="content">

		<?php include('admin_elements/breadcrumb.php');?>

		<form method="post" id="frm<?php echo $module;?>" name="frm<?php echo $module;?>" action="<?php echo $module;?>.php" class="form-horizontal" enctype="multipart/form-data">
		<?php if ( ($action=="edit_$module" || $action=="update_$module") && !empty($id) ){?>
		<input type="hidden" name="action" id="action" value="update_<?php echo $module;?>" />
		<input type="hidden" name="id" id="id" value="<?php echo $id;?>" />
		<?php } else {?>
		<input type="hidden" name="action" id="action" value="add_<?php echo $module;?>" />
		<?php }?>

		<div class="row">

			<div class="col-md-6">
					<div class="panel panel-flat">
					<div class="panel-heading">

						<div class="row">
							<div class="col-md-6"><h5><?php echo $module_caption;?> Details</h5></div>
						</div>

						<div class="panel-body">
							<div class="row">

								<div class="form-group">
								  <label class="col-lg-3 control-label text-semibold">List Name:</label>
								  <div class="col-lg-9">
								    <select class="form-control" name="list_id" id="list_id">
								      <?php
								        $result = $mysqli->query("SELECT * FROM `".$tbl_prefix."email_lists` WHERE id='".$list_id."'");
								        $rows = $result->fetch_array();
								      ?>
								      <option value="<?php echo $rows['id'];?>"
								        <?php if ($action=="edit_$module" && $rows['id']==$list_id){?>selected
								        <?php } else if ($rows['id']==$list_id){?>selected
								        <?php }?>
								      >
								      <?php echo $rows['list_name']?></option>
								    </select>
								  </div>
								</div>

								<div class="form-group">
								  <label class="col-lg-3 control-label text-semibold">Email:</label>
								  <div class="col-lg-9">
								    <input name="email" id="email" value="<?php echo $email;?>" class="form-control" type="text">
								  </div>
								</div>

								<div class="form-group">
								  <label class="col-lg-3 control-label text-semibold">First Name:</label>
								  <div class="col-lg-9">
								    <input name="first_name" id="first_name" value="<?php echo $first_name;?>" class="form-control" type="text">
								  </div>
								</div>

								<div class="form-group">
								  <label class="col-lg-3 control-label text-semibold">Last Name:</label>
								  <div class="col-lg-9">
								    <input name="last_name" id="last_name" value="<?php echo $last_name;?>" class="form-control" type="text">
								  </div>
								</div>

							</div>

							<div class="text-left">
								<button type="submit" class="btn btn-info"><?php if ( ($action=="edit_$module" || $action=="update_$module") && !empty($id) ){?>Update<?php } else { ?>Save<?php }?> <?php echo $module_caption;?></button>
								<a href="listing_<?php echo $module;?>.php"><button type="button" class="btn btn-default"> Back to <?php echo ucfirst($module_caption);?> Listing </button></a>
							</div>

					</div>
					</div>

			</div>
		</div>

		<?php include('admin_elements/copyright.php');?>
	</div>
	</form>
</div>
<?php include('admin_elements/admin_footer.php');?>
