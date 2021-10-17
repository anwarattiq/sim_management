<style>
  td {
  text-transform: capitalize !important;
  }
   #DivForHoverItem {
    /*just so we can see it*/
    height: 40px;
    width: 200px;
    background-color: red;
    margin:5px;
        padding: 5px;
    
   
}

#HiddenText {
    display: none;
}

#DivForHoverItem:hover{
    
     height: 40px; /*Height of bottom frame div*/ 
}
#DivForHoverItem:hover #HiddenText {
    display:block;
    color:#fff;
    
}
</style>
<?php
include('admin_elements/admin_header.php');
$module = 'sims_request';
	$module_caption = 'Sim Request Form';
	$tbl_name = $tbl_prefix . $module;
	
$tbl_name = $tbl_prefix . $module;
$photo_upload_path = '../uploads/' . $module . '/';

$error_message = '';
	$success_message = '';
	
	$user_id=$_SESSION['MT']['admin_id'];
	if($_SESSION['MT']['type']=='superadmin'){$result_sims = $mysqli->query("select * from  `$tbl_name` ");}else{ $result_sims = $mysqli->query("select * from  `$tbl_name` where user_id=$user_id");}
	
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


	/**
	 **************
	@@@ DELETE @@@
	 **************
	 **/
}


if($action=='delete_sims_request'){
	
	$srid=$_REQUEST['id'];
	
$result = $mysqli->query("Delete from `$tbl_name` WHERE id=$srid");
header('Location: listing_' . $module . '.php');
}
if($action=='reject'){
	
	$srid=$_REQUEST['rid'];
	$reason=$_REQUEST['reason'];
	$result = $mysqli->query("delete  from mt_sims_rejected  WHERE sims_request_id=$srid");
$insert_row = $mysqli->query( "INSERT INTO mt_sims_rejected(sims_request_id,reason) VALUES ('" . $srid . "','" . $reason . "'); ");
$result = $mysqli->query("UPDATE `$tbl_name` SET status='Rejected' WHERE id=$srid");
header('Location: listing_' . $module . '.php');
}

if($action=='invoice'){
  
    $pack=explode('-',$_REQUEST['package_name']);
    $p_id=$pack[0];
    $rate=$pack[1];
    $qty=$_REQUEST['qty'];
    $srid=$_REQUEST['rid'];
    $amount=$rate*$qty;
    
  
    $insert_row = $mysqli->query( "INSERT INTO mt_invoice(`sims_request_id`,`package_id`,`qty`,`amount`) VALUES ('" . $srid . "','" . $p_id . "','" . $qty . "','" . $amount . "'); ");

header('Location: listing_invoice.php');
    
}

?>
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

		<div class="row">
			<div class="col-md-12">

				<!-- <div class="text-center"><a href="<?php echo $module ?>.php"><button class="btn btn-info">Add <?php echo $module_caption; ?></button></a></div> -->

				<table id="grid4-<?php echo $module; ?>" class="display responsive no-wrap table table-striped table-bordered table-hover">
					<thead>
						<tr>
							<th >#</th>
							<th >Company</th>
							<th>Telcom Name</th>
							<th>Quantity</th>
							<th>Status</th>
							<th>File</th>
							<th >Manage</th>
							
							<!-- <th>Created</th> -->
							<!-- <th></th> -->
						</tr>
						
					</thead>
					<tbody>
					<?php 
					$i=1;
					foreach($result_sims as $array){
						
						echo '<tr>';
						echo '<td>'.$i.'</td><td>'.getTableAttr("company_name", $tbl_prefix . 'companies', $array['company']).'</td><td>'.$array['telecom_name'].'</td><td>'.$array['quantity'].'</td>';
						if($array['status']=='Rejected'){
				$html= '<div id="DivForHoverItem">
  <div id="HiddenText"><p>'.getTableAttrWhere("reason", $tbl_prefix . 'sims_rejected','sims_request_id', $array['id']).'</p></div>
</div>';
						}else{
						    $html=($_SESSION['MT']['type']=='superadmin')?'<button type="button" class="btn btn-waring" data-code="'.$array['id'].'"  data-qty="'.$array['quantity'].'"   data-toggle="modal" data-target="#myinvoice">
Generate Invoice
</button> </a>':'';
						}
					echo '<td>'.$array['status'].'<br />'.$html.'</td><td><a href="uploads/sims_request/' . $array['file'] . '" target="_blank">PDF</a></td>';
 if($_SESSION['MT']['type']=='superadmin'){ 				
			echo '<td><a href="listing_' . $module . '.php?action=accept&' . $module . '&tbl=' . $tbl . '&id=' . $array['id'] . '"><span class="label label-success">Accept</span></a> | <button type="button" class="btn btn-waring" data-code="'.$array['id'].'"  data-toggle="modal" data-target="#myModal">
Reject
</button>	'. ((($array['status']=='') || ($array['status']==null))? drawPublishedEditDelete($module, $array['id'], 0, 1, 1):'').'	</td>				</tr>
						';
 }else{
							
							echo '<td>'.((($array['status']=='') || ($array['status']==null))? drawPublishedEditDelete($module, $array['id'], 0, 1, 1):'').'</td><tr>';
						}
						
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

$(function () {
  $('#myinvoice').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); // Button that triggered the modal
    var code = button.data('code'); // Extract info from data-* attributes
    var qty = button.data('qty'); // Extract info from data-* attributes
    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
    var modal = $(this);
    modal.find('#code').val(code);
      modal.find('#qty').val(qty);
    
  });
});
</script>	
</div>




	<div class="modal" id="myinvoice">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Invoice Sims</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      
		<form method="POST" action="listing_sims_request.php?action=invoice" >
		
	

                                <div class="form-group">
                                    <label class="col-lg-3 control-label"><strong>Package Name</strong> <span class="f_req">*</span></label>
                                    <div class="col-lg-9">
                                        <select class="form-control" name="package_name" id="package_name">
                                            <option value=''>Please select</option>
                                            <?php
                                            $result = $mysqli->query("SELECT * FROM `" . $tbl_prefix . "sim_packages` WHERE publish=1 ORDER BY package_name");
                                            while ($rows = $result->fetch_array()) {
                                            ?>
                                                <option value="<?php echo $rows['id'].'-'.$rows['amount']; ?>" <?php if ($action == "edit_$module" && $rows['id'] == $package_name) { ?>selected <?php } else if ($rows['id'] == $package_name) { ?>selected <?php } ?>>
                                                    <?php echo $rows['package_name']; ?> - <?php echo $rows['amount']; ?> (IQD)
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                          
		<input type="hidden" id="code" name="rid" readonly />
       	<input type="hidden" id="qty" name="qty" readonly />
      </div>
      <div class="modal-footer">
          <br />
       <button type="submit" class="btn btn-primary">Generate</button>
		</form>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>















<?php include('admin_elements/admin_footer.php'); ?>