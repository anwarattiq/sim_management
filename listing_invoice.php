<?php
include('admin_elements/admin_header.php');
$module = 'invoice';
	$module_caption = 'Sim Invoice';
	$tbl_name = $tbl_prefix . $module;
	
$tbl_name = $tbl_prefix . $module;
$photo_upload_path = '../uploads/' . $module . '/';

$error_message = '';
	$success_message = '';
	$user_id=$_SESSION['MT']['admin_id'];
	if($_SESSION['MT']['type']=='superadmin'){$result_sims = $mysqli->query("select * from  `$tbl_name` ");}else{ $result_sims = $mysqli->query("SELECT * FROM `mt_invoice` INNER join mt_sims_request on mt_sims_request.id=mt_invoice.sims_request_id where mt_sims_request.user_id=$user_id");}	
#########################################

/**
 ****************
	@@@ FEATURED @@@
 ****************
 **/
 
if (($action == "accept" && !empty($id))) {

	$result = $mysqli->query("UPDATE `$tbl_name` SET status='Accepted' WHERE id=$id");
	if ($result)
		$success_message = "$module_caption set Status Successfully.";
	else
		$error_message = "Sorry! $module Could Not Be set Status.";
	header('Location: listing_' . $module . '.php');

	/**
	 ********************
	@@@ NOT FEATURED @@@
	 ********************
	 **/
} else if (($action == "paid" && !empty($_REQUEST['id']))) {
$id=$_REQUEST['id'];
	$result = $mysqli->query("UPDATE `$tbl_name` SET status='Paid' WHERE id=$id");
	if ($result)
		$success_message = "Status set  Successfully.";
	else
		$error_message = "Sorry! $module Could Not Be set as not featured.";
	header('Location: invoice.php?id='.$id);

	/**
	 ***************
	@@@ PUBLISH @@@
	 ***************
	 **/


	/**
	 **************
	@@@ DELETE @@@
	 **************
	 **/
}
///////////////////////////////////////////////////////////////////////////////



?>
<div class="content-wrapper">
	<div class="page-header page-header-default">
		<div class="page-header-content">
			<div class="page-title">
				<h4><i class="icon-circle-right2 position-left"></i> Listing <?php echo '<strong>' . ucwords($type) . '</strong>'; ?> <?php echo $module_caption; ?></h4>
				<a class="heading-elements-toggle"><i class="icon-more"></i></a>
			</div>
		
		</div>
	</div>
	<div class="content">

		<?php include('admin_elements/breadcrumb.php'); ?>

		<div class="row">
			<div class="col-md-12">

				<!-- <div class="text-center"><a href="<?php echo $module ?>.php"><button class="btn btn-info">Add <?php echo $module_caption; ?></button></a></div> -->

				<table id="grid4-<?php echo $module; ?>" class="display responsive no-wrap table table-striped table-bordered table-hover">
					<thead>
						<tr>
							<th >#</th>
						
							<th>Company</th>
								<th>Package</th>
							<th>Price</th>
							<th>Quantity</th>
							<th>Amount</th>
							<th>Status</th>
							<th >Manage</th>
							
							<!-- <th>Created</th> -->
							<!-- <th></th> -->
						</tr>
						
					</thead>
					<tbody>
					<?php 
					$i=1;
					foreach($result_sims as $array){
						
						$compan_id=getTableAttr("company", $tbl_prefix . 'sims_request', $array['sims_request_id']);
						echo '<tr>';
						echo '<td>'.$i.'</td><td>'.getTableAttr("company_name", $tbl_prefix . 'companies', $compan_id).'</td><td>'.getTableAttr("package_name", $tbl_prefix . 'sim_packages', $array['package_id']).'</td><td>'.getTableAttr("amount", $tbl_prefix . 'sim_packages', $array['package_id']).'</td><td>'.$array['qty'].'</td><td>'.$array['amount'].'</td>
						<td>'.$array['status'].'</td>';
						
						if($array['status']=='Paid'){
						  echo '<td><a href="invoice.php?id=' . $array['id'] . '"><span class="label label-success">Invoice Print</span></a>
</button></td>';  
						    
						}else{
					if($_SESSION['MT']['type']=='superadmin'){	
					echo	'<td><a href="listing_invoice.php?action=paid&' . $module . '&tbl=' . $tbl . '&id=' . $array['id'] . '"><span class="label bg-blue-400 has-bg-image">click to Pay</span></a>| <a href="invoice.php?id=' . $array['id'] . '"><span class="label label-success">Invoice Print</span></a>
</button></td>';}else{  echo '<td><a href="invoice.php?id=' . $array['id'] . '"><span class="label label-success">Invoice Print</span></a>
</button></td>';  
						      }} 
echo '</tr>';
					$i++;
					}
					
					?>
				</table>

			</div>
		</div>
	<div class="modal" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Reject Sims Request</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      
		<form method="POST" action="listing_sims_request.php?action=reject" >
		
	
	<label>Please enter Reason	</label><textarea name="reason"   required="required"></textarea>
	
		 
		<input type="hidden" id="code" name="rid" readonly />
       
      </div>
      <div class="modal-footer">
       <button type="submit" class="btn btn-primary">Save</button>
		</form>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
			<?php include('admin_elements/copyright.php'); ?>
	</div>
<script>
$(function () {
  $('#myModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); // Button that triggered the modal
    var code = button.data('code'); // Extract info from data-* attributes
    var company = button.data('company'); // Extract info from data-* attributes
    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
    var modal = $(this);
    modal.find('#code').val(code);
    modal.find('#company').val(company);
  });
});

</script>	
</div>
<?php include('admin_elements/admin_footer.php'); ?>