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
				 	   @@@ PUBLISH @@@
	**************************************
	**/
	if ( ($action=="publish_$module" && !empty($id))){

		if ( publish($module_caption, $tbl_name, $id) )
			$success_message = "$module_caption Published Successfully.";
		else
			$error_message = "Sorry! $module Could Not Be Published.";

	/**
	**************************************
				 	   @@@ PUBLISH @@@
	**************************************
	**/
	} else if ( ($action=="unpublish_$module" && !empty($id))){

		if ( unpublish($module_caption, $tbl_name, $id) )
			$success_message = "$module_caption Un-Published Successfully.";
		else
			$error_message = "Sorry! $module Could Not Be Un-Published.";


	/**
	**************************************
			 	   @@@ DELETE @@@
	**************************************
	**/
	} else if ( ($action=="delete_$module" && !empty($id))){

		//SUPERADMIN CAN DELETE ANY DATA
		if ($_SESSION[$project_pre]['type']=='superadmin'){
			$result = $mysqli->query("DELETE FROM `$tbl_name` WHERE id='".$id."'");

		//ADMIN CAN DELETE ONLY HIS/HER DATA
		} else {
			$result = $mysqli->query("DELETE FROM `$tbl_name` WHERE id='".$id."' AND created_by='".$_SESSION[$project_pre]['admin_id']."'");

		}

		if($result){
			$success_message = "$module_caption Deleted Successfully.";
			header("Location:listing_$module.php?page=$page&success_message=$success_message");
		} else {
			$error_message = "Sorry! $module Could Not Be Deleted.";
		}
	}
  ///////////////////////////////////////////////////////////////////////////////
?>
<div class="content-wrapper">
	<div class="page-header page-header-default">
		<div class="page-header-content">
			<div class="page-title">
				<h4><i class="icon-circle-right2 position-left"></i> Listing <?php echo $module_caption;?></h4>
				<a class="heading-elements-toggle"><i class="icon-more"></i></a>
			</div>
		</div>
	</div>
	<div class="content">

		<?php include('admin_elements/breadcrumb.php');?>

		<div class="row">
			<div class="col-md-12">

		        <table id="grid-<?php echo $module;?>" class="table table-striped table-bordered table-hover">
		            <thead>
		            <tr>
									<th width="40"><input type="checkbox"  id="bulkDelete"  /> <button id="deleteTriger"> Delete </button></th>
                  <th>Email</th>
                  <th>First Name</th>
                  <th>Last Name</th>
                  <th>List Name</th>
                  <th>Status</th>
                  <th>Created By</th>
                  <th>Created On</th>
                  <th width="40">Manage</th>
		            </tr>
		          </thead>
		        </table>

			</div>
		</div>

		<?php include('admin_elements/copyright.php');?>
	</div>
</div>
<?php include('admin_elements/admin_footer.php');?>
