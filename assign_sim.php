<?php
include('admin_elements/admin_header.php');
$module = 'employees_sims';
$module_caption = 'SIMs Assignment';
$tbl_name = $tbl_prefix . $module;

$error_message = '';
$success_message = '';
#########################################

/**
 ************************************
@@@ GET ALL VARIABLES ADD/UPDATE @@@
 ************************************
 **/
if ($action == "update_$module") {
    $sim_number                     = $_POST['sim_number'];
} else {
    $sim_number                     = '';
}


if (!empty($id)) {

    $result = $mysqli->query("SELECT * FROM `" . $GLOBALS['TBL']['PREFIX'] . "sims` WHERE id=$id");
    $row = $result->fetch_array();

    $sim_number                  = stripslashes($row['sim_number']);
    // $created                     = substr($created, 0, -9); // . ' 00:00:00';
    // $created                     = processDateYtoD($created);
    // $publish                     = stripslashes($row['publish']);
}


/**
 **************
@@@ UPDATE @@@
 **************
 **/
if ($action == "update_$module") {

    if (empty($sim_number) || $sim_number == 'Please select') {
        $error_message = 'SIM Number is mandatory.';
    } else {

        $created        = processDateDtoY($created);
        $created        = $created . ' 00:00:00';;

        $update_row = $mysqli->query("
				UPDATE `$tbl_name` SET
					sim_number						= '" . $sim_numbers_ids . "',
					slug							= '" . $slug . "',
					created							= '" . $created . "',
					publish 						= '" . $publish . "'
				WHERE id=$id");

        if ($update_row) {
            $success_message = "$module_caption Updated Successfully.";
            fp__($tbl_name, $id);
            // header("Location:listing_$module.php?success_message=$success_message");
        } else {
            $error_message = "Sorry ! $module_caption Could Not Be Updated.";
            //header("Location:$module.php?action=edit_$module&id=$id&error_message=$error_message");
        }
    }
    /**
     ***********
	 @@@ ADD @@@
     ***********
     **/
} else if ($action == "add_$module") {
 $sim_number                     = $_POST['sim_number'];
 $employee_id                     = $_POST['employee_id'];
 $start_date                     = $_POST['start_date'];
    if (empty($sim_number) || $sim_number == 'Please select') {
        $error_message = 'SIM Number is mandatory.';
    } else {

 
        $insert_row = $mysqli->query("INSERT INTO `$tbl_name`(employee_id,sim_id,start_date	) VALUES ('" . $employee_id . "', '" .$sim_number. "', '" . $start_date . "'); ");

        if ($insert_row) {
            $id = $mysqli->insert_id;
            fp__($tbl_name, $id);
            $success_message = "assign sim Saved Successfully.";
            header("Location:listing_sims.php?success_message=$success_message");
            //////////////////////////////////////////////////
        } else {
            $error_message .= "Sorry ! $module_caption Could Not Be Saved.";
              header("Location:listing_sims.php?error_message=$error_message");
        }
    }
}


/**
 ************
 @@@ EDIT @@@
 ************
 **/

if (!empty($id)) {

    $result = $mysqli->query("SELECT * FROM `$tbl_name` WHERE id=$id");
    $row = $result->fetch_array();

    // $sim_number                  = stripslashes($row['sim_number']);
    // $created                     = substr($created, 0, -9); // . ' 00:00:00';
    // $created                     = processDateYtoD($created);
    // $publish                     = stripslashes($row['publish']);
}
///////////////////////////////////////////////////////////////////////////////



?>

<div class="content-wrapper">

    <?php include('admin_elements/breadcrumb.php'); ?>

    <!-- Content area -->
    <div class="content">

        <!-- Dashboard content -->
        <div class="row">
            <div class="col-lg-12">

                <!-- Quick stats boxes -->
                <form method="post" id="frm<?php echo $module; ?>" name="frm<?php echo $module; ?>" action="assign_sim.php" enctype="multipart/form-data">
                    <input type="hidden" name="action" id="action" value="add_<?php echo $module; ?>" />
<input type="hidden" name="sim_number" value="<?php echo  $id ; ?>" />
                     <div class="row">

                        <div class="col-md-6">
                            <div class="panel panel-flat">
                                <div class="panel-heading">

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-9">
                                                <h5>Assign <?php echo getTableAttr("sim_number", $GLOBALS['TBL']['PREFIX'] . 'sims', $id); ?></h5>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="text-left">
                                                    <button type="submit" class="btn btn-info">Assign</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="panel-body">
                                    <div class="alert alert-info">
                                        <?php echo getTableAttr("sim_number", $GLOBALS['TBL']['PREFIX'] . 'sims', $id); ?>
                                    </div>
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
                                                            <option value="<?php echo $rows['id']; ?>" <?php if ($action == "edit_$module" && in_array($rows['id'], $sim_number_arr)) { ?>selected <?php } else if ($rows['id'] == $sim_number) { ?>selected <?php } ?>>
                                                                <?php echo $rows['employee_name'].' ('.getTableAttr("company_name", $tbl_prefix . 'companies', $rows['company']).')'; ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            
                                    <div class="form-group">
                                        <label><strong>Start Date</strong> <span class="f_req">*</span></label>
                                        <input type="date" name="start_date" id="start_date" class="form-control" required="required" />
                                    </div>

                                </div>

                            </div>
                        </div>


                        
                </form>



            </div>


        </div>
    </div>
    <!-- /dashboard content -->

    <?php include('admin_elements/copyright.php'); ?>

</div>
<!-- /content area -->

</div>

<?php include('admin_elements/admin_footer.php'); ?>