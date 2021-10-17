<?php
	include('admin_elements/admin_header.php');
	$module = 'emails';
	$module_caption = 'Email';
	$tbl_name = $tbl_prefix.$module;
	$error_message = ''; $success_message = '';
	#########################################

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
  include('admin_elements/errors.php');
?>
<!-- BEGIN PAGE CONTENT INNER -->
<div class="page-content-inner">

    <div class="portlet box green">
        <div class="portlet-title">
          <div class="caption"> <i class="fa fa-globe"></i>Listing <?php echo ucwords($module);?> </div>
          <div class="tools"> </div>
        </div>
        <div class="portlet-body">
          <div id="sample_2_wrapper" class="dataTables_wrapper no-footer">

            <div class="row">
              <div class="col-md-12">
                <div class="dt-buttons">
                    <a class="dt-button buttons-print btn default" tabindex="0" aria-controls="sample_2" href="<?php echo $module?>.php"><span>Create <?php echo $module_caption;?></span></a>
                </div>
              </div>
            </div>

            <table id="grid-<?php echo $module;?>" class="table">
                <thead>
                <tr>
                  <th><input type="checkbox"  id="bulkDelete"  /> <button id="deleteTriger"> Delete </button></th>
                  <th>Email</th>
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
    </div>

</div>
<!-- END PAGE CONTENT INNER -->
<?php
include('admin_elements/admin_footer.php');
