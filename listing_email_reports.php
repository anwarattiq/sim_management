<?php
	include('admin_elements/admin_header.php');
	$module = 'email_reports';
	$module_caption = 'Email Reports';
	$tbl_name = $tbl_prefix.$module;
	$error_message = ''; $success_message = '';
	#########################################

	$camp_id = '';
	if (isset($_REQUEST['camp_id']) && !empty($_REQUEST['camp_id'])) 	$camp_id = $_REQUEST['camp_id'];

	if (empty($camp_id)) header("Location:listing_email_campaigns.php");

	$campaign_name = getTableAttr("campaign_name", $tbl_prefix.'email_campaigns', $camp_id);
	$list_id       = getTableAttr("list_id", $tbl_prefix.'email_campaigns', $camp_id);
	$list_name     = getTableAttr("list_name", $tbl_prefix.'email_lists', $list_id);

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


	// SENT
	$sent      = 0;
	$rs_status      = $mysqli->query("SELECT count(id) FROM `".$GLOBALS['TBL']['PREFIX']."email_reports` WHERE campaign_id=$camp_id AND status>0");
	$row_status     = $rs_status->fetch_array();
	$sent      = $row_status[0];

	// OPENS - status=2
	$total_o   = 0;
	$rs_o      = $mysqli->query("SELECT count(id) FROM `".$GLOBALS['TBL']['PREFIX']."email_reports` WHERE campaign_id=$camp_id AND status=2");
	$row_o     = $rs_o->fetch_array();
	$opens     = $row_o[0];

	// // CLICKS - status=3
	$total_c   = 0;
	$rs_c      = $mysqli->query("SELECT count(id) FROM `".$GLOBALS['TBL']['PREFIX']."email_reports` WHERE campaign_id=$camp_id AND status=3");
	$row_c     = $rs_c->fetch_array();
	$clicks   = $row_c[0];

	// // Total Campaign Emails
	$total_t   = 0;
	$rs_t      = $mysqli->query("SELECT count(id) FROM `".$GLOBALS['TBL']['PREFIX']."email_reports` WHERE campaign_id=$camp_id");
	$row_t     = $rs_t->fetch_array();
	$total   	= $row_t[0];

	$remaining = $total - $sent;

  ///////////////////////////////////////////////////////////////////////////////
?>
<div class="content-wrapper">
	<div class="page-header page-header-default">
		<div class="page-header-content">
			<div class="page-title">
				<h4 class="text-center"><i class="icon-circle-right2 position-left"></i>
					<strong><?php echo $campaign_name?></strong> <small>(<?php echo $remaining?> reamining / <?php echo $total;?> total)</small><br />
				</h4>
				List: <?php echo $list_name?><br />
				<a class="heading-elements-toggle"><i class="icon-more"></i></a>
				<span class="label label-success">Sent: <?php echo $sent;?></span>
				<span class="label label-warning">Opens: <?php echo $opens;?></span>
				<span class="label label-info">Clicks: <?php echo $clicks;?></span>
				<span class="label label-danger">Pending: <?php echo $remaining;?></span>
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
									<th>Email</th>
									<th>Status</th>
									<th>Sent</th>
									<!-- <th>Campaign</th> -->
									<!-- <th>List</th> -->
		            </tr>
		          </thead>
		        </table>

			</div>
		</div>

		<?php include('admin_elements/copyright.php');?>
	</div>
</div>
<?php include('admin_elements/admin_footer.php');?>
