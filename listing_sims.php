<?php
include('admin_elements/admin_header.php');
$module = 'sims';
$module_caption = 'Sim Number';
$tbl_name = $tbl_prefix . $module;
$photo_upload_path = '../uploads/' . $module . '/';
$error_message = '';
$success_message = '';
#########################################

/**
 ****************
	@@@ FEATURED @@@
 ****************
 **/
if (($action == "featured_$module" && !empty($id))) {

	$result = $mysqli->query("UPDATE `$tbl_name` SET featured=1 WHERE id=$id");
	if ($result)
		$success_message = "$module_caption set featured Successfully.";
	else
		$error_message = "Sorry! $module Could Not Be set featured.";

	/**
	 ********************
	@@@ NOT FEATURED @@@
	 ********************
	 **/
} else if (($action == "nofeatured_$module" && !empty($id))) {

	$result = $mysqli->query("UPDATE `$tbl_name` SET featured=0 WHERE id=$id");
	if ($result)
		$success_message = "$module_caption set as not featured Successfully.";
	else
		$error_message = "Sorry! $module Could Not Be set as not featured.";

	/**
	 ***************
	@@@ PUBLISH @@@
	 ***************
	 **/
} else if (($action == "publish_$module" && !empty($id))) {

	if (publish($module_caption, $tbl_name, $id))
		$success_message = "$module_caption Published Successfully.";
	else
		$error_message = "Sorry! $module Could Not Be Published.";

	/**
	 ***************
	@@@ PUBLISH @@@
	 ***************
	 **/
} else if (($action == "unpublish_$module" && !empty($id))) {

	if (unpublish($module_caption, $tbl_name, $id))
		$success_message = "$module_caption Un-Published Successfully.";
	else
		$error_message = "Sorry! $module Could Not Be Un-Published.";


	/**
	 **************
	@@@ DELETE @@@
	 **************
	 **/
} else if (($action == "delete_$module" && !empty($id))) {

	//SUPERADMIN CAN DELETE ANY DATA
	if ($_SESSION[$project_pre]['type'] == 'superadmin') {

		$photo = get_photo_name('photo', $id, $tbl_name);
		$result = $mysqli->query("DELETE FROM `$tbl_name` WHERE id=$id");

		// DELETE OLD PHOTO
		if (!empty($photo)) delete_photo($photo, $photo_upload_path);

		//ADMIN CAN DELETE ONLY HIS/HER DATA
	} else {

		$photo = get_photo_name('photo', $id, $tbl_name);
		$result = $mysqli->query("DELETE FROM `$tbl_name` WHERE id=$id AND created_by='" . $_SESSION[$project_pre]['admin_id'] . "'");

		// DELETE OLD PHOTO
		if (!empty($photo)) delete_photo($photo, $photo_upload_path);
	}


	if ($result) {
		$success_message = "$module_caption Deleted Successfully.";
		header("Location:listing_$module.php?page=$page&success_message=$success_message");
	} else {
		$error_message = "Sorry! $module Could Not Be Deleted.";
	}
}
///////////////////////////////////////////////////////////////////////////////
?>
<style type="text/css">
	.dataTables_length > label {
    margin-bottom: 0;
    margin-left: 148%;
}
</style>
<div class="content-wrapper">
	<div class="page-header page-header-default">
		<div class="page-header-content">
			<div class="page-title">
				<h4><i class="icon-circle-right2 position-left"></i> Listing <?php echo '<strong>' . ucwords($type) . '</strong>'; ?> <?php echo $module_caption; ?></h4>
				<a class="heading-elements-toggle"><i class="icon-more"></i></a>
			</div>
			<div class="heading-elements">
				<div class="heading-btn-group">
					<a href="<?php echo $module; ?>.php"><button class="btn btn-info">Add <?php echo $module_caption; ?></button></a>
				</div>
			</div>
		</div>
	</div>
	<div class="content">

		<?php include('admin_elements/breadcrumb.php'); ?>

<!-- The Modal -->
  <div class="modal" id="assignMultiSim">
    <div class="modal-dialog">
      <div class="modal-content">
      <form method="POST" action="assign_sim_multiple.php">
        <!-- Modal Header -->
        <input type="hidden" id="sim_number" name="sim_number" value="">

        <div class="modal-header bg-primary">
          <h4 class="modal-title text-white">Assign Multiple Sims</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
          
 <div class="form-group">
                <label><strong>Employee</strong> <span class="f_req">*</span></label>
                <select class="form-control" name="employee_id" id="employee_id"  required>
                    <?php if (($action == "add_$module") || empty($id)) { ?>
                        <option value='1' selected>Uncategorized</option>
                    <?php } ?>
                    <?php
                    $result = $mysqli->query("SELECT t1.id,company,employee_name  FROM mt_employees t1 LEFT JOIN mt_employees_sims t2 ON t2.employee_id = t1.id WHERE t2.employee_id IS NULL");
                    
                    
                    
                    
                    while ($rows = $result->fetch_array()) {
                    ?>
                     
                        <option value="<?php echo $rows['id']; ?>"><?php echo $rows['employee_name'];  ?></option>
                    <?php } ?>
                </select>

  </div>

  <div class="form-group">
    <label><strong>Start Date</strong> <span class="f_req">*</span></label>
    <input type="date" name="start_date" id="start_date" class="form-control" required="required" />
  </div>

        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="submit" class="btn btn-success" >Save</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
        </form>
      </div>
    </div>
  </div>

		<div class="row">
			<div class="col-md-12">

				<!-- <div class="text-center"><a href="<?php echo $module ?>.php"><button class="btn btn-info">Add <?php echo $module_caption; ?></button></a></div> -->

				<table id="grid-<?php echo $module; ?>" class="display responsive no-wrap table table-striped table-bordered table-hover">

					

					<thead>
						<tr>
							<th width="40">Manage</th>
							<th width="100">Telecom Name</th>
							<th>SIM Number</th>
								<th>Employee Name</th>
							<th>Package Name</th>
							<th>Amount (IQD)</th>
							<!-- <th>Created</th> -->
							<!-- <th></th> -->
						</tr>
					</thead>
				</table>

			</div>
		</div>
		<div class="alert alert-info alert-styled-left alert-arrow-left alert-component">
			<ul>
				<li><button class="btn btn-info">11 digits</button></a> Standard format for SIM Number.</li>
			</ul>
		</div>
		<?php include('admin_elements/copyright.php'); ?>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$('#grid-sims_length').append('<button style="margin-left: -20px;" class="btn btn-success" onclick="assignMultiple()">Assign Multiple</button>')	
	});
	function assignMultiple() {
		simsArray=[];
		 $.each($("input[name='all_sims']:checked"), function (K, V) {    
         simsArray.push(V.value);        
         });
         //alert(simsArray)
         if (simsArray.length < 2) { 
		  alert("Please check atleast two sim numbers for Multiple Assignment") 
	    } else{
	    	$('#assignMultiSim').modal();
	    	var sim_numbers = simsArray.join(','); 
	    	$('#sim_number').val(sim_numbers);
	    }


	}



</script>
<?php include('admin_elements/admin_footer.php'); ?>
