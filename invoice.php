<?php
include('admin_elements/admin_header.php');
$module = 'invoice';
	$module_caption = 'Sim Invoice';
	$tbl_name = $tbl_prefix . $module;
	
$tbl_name = $tbl_prefix . $module;
$photo_upload_path = '../uploads/' . $module . '/';

$error_message = '';
	$success_message = '';
	$id=$_REQUEST['id'];

$result_sims = $mysqli->query("select * from  `$tbl_name` where id='$id'");
		$total_amount=$html=$company_name='';
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
///////////////////////////////////////////////////////////////////////////////



?>
<div class="content-wrapper">
	<div class="page-header page-header-default">
		<div class="page-header-content">
			<div class="page-title">
				<h4><i class="icon-circle-right2 position-left"></i>  <?php echo '<strong>' . ucwords($type) . '</strong>'; ?> <?php echo $module_caption; ?></h4>
				<a class="heading-elements-toggle"><i class="icon-more"></i></a>
		
			</div>
		
		</div>
	</div>
	<div class="content">

		<?php include('admin_elements/breadcrumb.php'); ?>

		<div class="row">
		

			<div class="col-md-12">
<style>
   

.invoice {
    background: #fff;
    padding: 20px
}

.invoice-company {
    font-size: 20px
}

.invoice-header {
    margin: 0 -20px;
    background: #f0f3f4;
    padding: 20px
}

.invoice-date,
.invoice-from,
.invoice-to {
    display: table-cell;
    width: 1%
}

.invoice-from,
.invoice-to {
    padding-right: 20px
}

.invoice-date .date,
.invoice-from strong,
.invoice-to strong {
    font-size: 16px;
    font-weight: 600
}

.invoice-date {
    text-align: right;
    padding-left: 20px
}

.invoice-price {
    background: #f0f3f4;
    display: table;
    width: 100%
}

.invoice-price .invoice-price-left,
.invoice-price .invoice-price-right {
    display: table-cell;
    padding: 20px;
    font-size: 20px;
    font-weight: 600;
    width: 75%;
    position: relative;
    vertical-align: middle
}

.invoice-price .invoice-price-left .sub-price {
    display: table-cell;
    vertical-align: middle;
    padding: 0 20px
}

.invoice-price small {
    font-size: 12px;
    font-weight: 400;
    display: block
}

.invoice-price .invoice-price-row {
    display: table;
    float: left
}

.invoice-price .invoice-price-right {
    width: 25%;
    background: #2d353c;
    color: #fff;
    font-size: 28px;
    text-align: right;
    vertical-align: bottom;
    font-weight: 300
}

.invoice-price .invoice-price-right small {
    display: block;
    opacity: .6;
    position: absolute;
    top: 10px;
    left: 10px;
    font-size: 12px
}

.invoice-footer {
    border-top: 1px solid #ddd;
    padding-top: 10px;
    font-size: 10px
}

.invoice-note {
    color: #999;
    margin-top: 80px;
    font-size: 85%
}

.invoice>div:not(.invoice-footer) {
    margin-bottom: 20px
}

.btn.btn-white, .btn.btn-white.disabled, .btn.btn-white.disabled:focus, .btn.btn-white.disabled:hover, .btn.btn-white[disabled], .btn.btn-white[disabled]:focus, .btn.btn-white[disabled]:hover {
    color: #2d353c;
    background: #fff;
    border-color: #d9dfe3;
}
</style>

	<?php 
					$i=1;
					foreach($result_sims as $array){
						$total_amount=$array['amount'];
						$compan_id=getTableAttr("company", $tbl_prefix . 'sims_request', $array['sims_request_id']);
						$company_name=getTableAttr("company_name", $tbl_prefix . 'companies', $compan_id);
						$html.= '<tr><td>'.$i.'</td><td>'.$company_name.'</td><td>'.getTableAttr("package_name", $tbl_prefix . 'sim_packages', $array['package_id']).'</td><td>'.getTableAttr("amount", $tbl_prefix . 'sim_packages', $array['package_id']).'</td><td>'.$array['qty'].'</td><td>'.$array['amount'].'</td>
						<td>'.$array['status'].'</td>
						
										</tr>
						';
					$i++;
					}
					
					?>



      <div class="invoice">
         <!-- begin invoice-company -->
         <div class="invoice-company text-inverse f-w-600">
            <span class="pull-right hidden-print">
            <a href="javascript:;" onclick="window.print()" class="btn btn-sm btn-white m-b-10 p-l-5"><i class="fa fa-print t-plus-1 fa-fw fa-lg"></i> Print</a>
         
           
            </span>
            Fusteka Group 
         </div>
         <!-- end invoice-company -->
         <!-- begin invoice-header -->
         <div class="invoice-header">
            <div class="invoice-from">
               <small>from</small>
               <address class="m-t-5 m-b-5">
                  <strong class="text-inverse">Fusteka Group</strong><br>
                   Address : Office No# 4<br>
                  City, Zip Code:BASRAH, IRAQ, 452548<br>
                  Phone:+964 (773) 852 0302<br>
                  Fax: (123) 456-7890
               </address>
            </div>
            <div class="invoice-to">
               <small>to</small>
               <address class="m-t-5 m-b-5">
                  <strong class="text-inverse"><?php echo $company_name;?></strong><br>
                  Street Address<br>
                  City, Zip Code<br>
                  Phone: (123) 456-7890<br>
                  Fax: (123) 456-7890
               </address>
            </div>
            <div class="invoice-date">
               <small>Invoice </small>
               <div class="date text-inverse m-t-5">August ,2021</div>
               <div class="invoice-detail">
                  #0000<?php echo $id;?><br>
                  Sims Product
               </div>
            </div>
         </div>
         <!-- end invoice-header -->
         <!-- begin invoice-content -->
         <div class="invoice-content">
            <!-- begin table-responsive -->
            <div class="table-responsive">
            	<table class="display responsive no-wrap table table-striped table-bordered table-hover">
					<thead>
						<tr>
							<th >#</th>
						
							<th>Company</th>
							<th>Package</th>
							<th>Price</th>
							<th>Quantity</th>
							<th>Amount</th>
							<th>Status</th>
						
							
							<!-- <th>Created</th> -->
							<!-- <th></th> -->
						</tr>
						
					</thead>
					<tbody>
					<?php 
					$i=1;
					foreach($result_sims as $array){
						$total_amount=$array['amount'];
						$compan_id=getTableAttr("company", $tbl_prefix . 'sims_request', $array['sims_request_id']);
						echo '<tr>';
						echo '<td>'.$i.'</td><td>'.getTableAttr("company_name", $tbl_prefix . 'companies', $compan_id).'</td><td>'.getTableAttr("package_name", $tbl_prefix . 'sim_packages', $array['package_id']).'</td><td>'.getTableAttr("amount", $tbl_prefix . 'sim_packages', $array['package_id']).'</td><td>'.$array['qty'].'</td><td>'.$array['amount'].'</td>
						<td>'.$array['status'].'</td>
						
										</tr>
						';
					$i++;
					}
					
					?>
				</table>

            </div>
            <!-- end table-responsive -->
            <!-- begin invoice-price -->
            <div class="invoice-price">
               <div class="invoice-price-left">
                  <div class="invoice-price-row">
                     <div class="sub-price">
                        <small>SUBTOTAL</small>
                        <span class="text-inverse"><?php echo $total_amount;?></span>
                     </div>
                     <div class="sub-price">
                        <i class="fa fa-plus text-muted"></i>
                     </div>
                     <div class="sub-price">
                        <small>PAYPAL FEE (5.4%)</small>
                        <span class="text-inverse">0.00</span>
                     </div>
                  </div>
               </div>
               <div class="invoice-price-right">
                  <small>TOTAL</small> <span class="f-w-600"><?php echo $total_amount;?></span>
               </div>
            </div>
            <!-- end invoice-price -->
         </div>
         <!-- end invoice-content -->
         <!-- begin invoice-note -->
         <div class="invoice-note">
            * Make all cheques payable to [Fusteka ]<br>
            * Payment is due within 30 days<br>
            * If you have any questions concerning this invoice, contact  [Imran, Phone Number, imran@shopin.com]
         </div>
         <!-- end invoice-note -->
         <!-- begin invoice-footer -->
         <div class="invoice-footer">
            <p class="text-center m-b-5 f-w-600">
               THANK YOU FOR YOUR BUSINESS
            </p>
            <p class="text-center">
               <span class="m-r-10"><i class="fa fa-fw fa-lg fa-globe"></i> shopin.com</span>
               <span class="m-r-10"><i class="fa fa-fw fa-lg fa-phone-volume"></i> T:016-18192302</span>
               <span class="m-r-10"><i class="fa fa-fw fa-lg fa-envelope"></i> info@shopin.com</span>
            </p>
         </div>
         <!-- end invoice-footer -->
      </div>
   </div>
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