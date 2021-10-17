<?php
	include('admin_elements/admin_header.php');
	$module = 'searches';
	$module_caption = 'Searches';
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
	//////////////////////////////////////////////////////////////
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
						<th width="40">Manage</th>
						<th>Date</th>
						<th>AMP</th>
		              	<th>Search Keyword</th>
		              	<th>IP</th>
		            </tr>
		          </thead>
		        </table>

			</div>
		</div>

		<?php include('admin_elements/copyright.php');?>
	</div>
</div>
<?php include('admin_elements/admin_footer.php');?>
