<?php 
	include('admin_elements/admin_header.php');
	include('admin_elements/only_superadmin.php');
	$module = 'admin_activities';
	$module_caption = 'Admin Activities';
	$tbl_name = $tbl_prefix.$module;
	$error_message = ''; $success_message = '';	
	#########################################		
	
	/**
	**************************************
			 	   @@@ DELETE @@@
	**************************************
	**/	
	if ( $action=="delete_$module" && !empty($id) && $_SESSION[$project_pre]['type']=='superadmin'){
		
		$result = $mysqli->query("DELETE FROM `$tbl_name` WHERE id=$id");
		if($result){
			$success_message = "$module_caption Deleted Successfully.";
			header("Location:listing_$module.php?page=$page&success_message=$success_message");
		} else {
			$error_message = "Sorry! $module Could Not Be Deleted.";			
		}
	}
	///////////////////////////////////////////////////////////////////////////////
?>
  <div id="contentwrapper">
    <div class="main_content">      
  		<?php include('admin_elements/breadcrumb.php');?>

        <table id="grid-<?php echo $module;?>" class="table">
            <thead>
            <tr>
            	<th width="10"></th>
              <th>Admin ID</th>
              <th>IP Address</th>
              <th>Activity</th>
              <th>Activity Time</th>
            </tr>
          </thead>
        </table>
            
    </div> 
    <!--END CONTENT-->
  </div>
</div>
<?php include('admin_elements/sidebar.php');?>  
<?php include('admin_elements/admin_footer.php');?>  