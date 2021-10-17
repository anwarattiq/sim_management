<?php
include('admin_elements/admin_header.php');
include('admin_elements/only_superadmin.php');
$module = 'employees';
$module_caption = 'Employee';
$tbl_name = $tbl_prefix . $module;
$error_message = '';
$success_message = '';

/**
 **************************************
			 	   @@@ DELETE @@@
	 ONLY SUPERADMIN CAN DELETE ACCOUNTS
 **************************************
 **/
if (($action == "delete_$module" && $_SESSION[$project_pre]['type'] == 'superadmin' && !empty($id) && ($id != 1))) {

    $result = $mysqli->query("DELETE FROM `$tbl_name` WHERE id=$id && id!=1");

    if ($result) {

        // DELETE ADMIN CATEGORIES
        $result = $mysqli->query("DELETE FROM `" . $tbl_prefix . "categories` WHERE created_by=$id");

        // DELETE ADMIN BLOCKS
        $result = $mysqli->query("DELETE FROM `" . $tbl_prefix . "admin_blocks` WHERE admin_id=$id");

        // DELETE ADMIN LOGS
        $result = $mysqli->query("DELETE FROM `" . $tbl_prefix . "admin_logs` WHERE admin_id=$id");


        $success_message = "$module_caption Deleted Successfully.";
        header("Location:listing_$module.php?page=$page&success_message=$success_message");
    } else {
        $error_message = "Sorry! $module Could Not Be Deleted.";
    }
}
#########################################
?>
<div class="content-wrapper">
    <div class="page-header page-header-default">
        <div class="page-header-content">
            <div class="page-title">
                <h4><i class="icon-circle-right2 position-left"></i> Listing <?php echo $module_caption; ?>s</h4>
                <a class="heading-elements-toggle"><i class="icon-more"></i></a>
            </div>
            <div class="heading-elements">
                <div class="heading-btn-group">
                    <a href="<?php echo $module ?>.php"><button class="btn btn-info">Add <?php echo $module_caption; ?></button></a>
                </div>
            </div>
        </div>
    </div>
    <div class="content">

        <?php include('admin_elements/breadcrumb.php'); ?>

        <div class="row">
            <div class="col-md-12">
<?php // echo $module ?>
                <!-- <div class="text-center"><a href="<?php echo $module ?>.php"><button class="btn btn-info">Add <?php echo $module_caption; ?></button></a></div> -->
                <table id="grid-<?php echo $module; ?>" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th width="40">Manage</th>
                            <th>Employee Name</th>
                            <th>Company</th>
                            <th width="50">Staff ID</th>
                            <th>City</th>
                            <th>Job Title</th>
                             <th>Sim</th>
                              <th>Mobile Company</th>
                               <th>Package</th>
                            <th>Address</th>
                        </tr>
                    </thead>
                </table>

            </div>
        </div>

        <?php include('admin_elements/copyright.php'); ?>
    </div>
</div>
<?php include('admin_elements/admin_footer.php'); ?>