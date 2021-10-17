<?php
include('admin_elements/admin_header.php');
$module = 'sims';
$module_caption = 'Sim';
$tbl_name = $tbl_prefix . $module;
$error_message = '';
$success_message = '';
#########################################
// if (isset($_POST['publish']))                             $publish = 1;
// else $publish = 0;
$publish = 0;
if (isset($_POST['views']) && !empty($_POST['views']))     $views = $mysqli->real_escape_string(stripslashes($_POST['views']));
else $views = 0;
if (isset($_POST['bit']) && !empty($_POST['bit']))         $bit = $mysqli->real_escape_string(stripslashes($_POST['bit']));
else $bit = 0;

/**
 **************************************
   @@@ GET ALL VARIABLES ADD/UPDATE @@@
 **************************************
 **/
if ($action == "update_$module" || $action == "add_$module") {
    $sim_number                  = $mysqli->real_escape_string(stripslashes($_POST['sim_number']));
    $telecom_name                = $mysqli->real_escape_string(stripslashes($_POST['telecom_name']));
    $package_name                = $mysqli->real_escape_string(stripslashes($_POST['package_name']));
    // $amount                     = $mysqli->real_escape_string(stripslashes($_POST['amount']));
} else {
    $sim_number                  = '';
    $telecom_name                = '';
    $package_name                = '';
    // $amount                     = '';
}

/**
 **************
	@@@ UPDATE @@@
 **************
 **/
if ($action == "update_$module" && !empty($id)) {
    if (empty($telecom_name) || $telecom_name == 'Please select') {
        $error_message = 'Telecom name is mandatory.';
    } else if (empty($package_name) || $package_name == 'Please select') {
        $error_message = 'Package name is mandatory.';
    } else if (empty($sim_number)) {
        $error_message = 'SIM Number is mandatory.';
    } else if (strlen($sim_number) < 11) {
        $error_message = 'SIM Number is not Valid.';
    } else {

        $amount = getTableAttr("amount", $tbl_prefix . 'sim_packages', $package_name);

        //////////////////////////////////////////////////
        $update_row = $mysqli->query("
							UPDATE `$tbl_name` SET
								sim_number				= '" . $sim_number . "',
								telecom_name			= '" . $telecom_name . "',
								package_name			= '" . $package_name . "',
								amount			        = '" . $amount . "',
								publish 				= '" . $publish . "'
							WHERE id=$id");

        if ($update_row) {
            $success_message = "$module_caption Updated Successfully.";
            fp__($tbl_name, $id);
            // header("Location:listing_$module.php?success_message=$success_message");
        } else {
            $error_message = "Sorry ! $module_caption Could Not Be Updated.";
            header("Location:$module.php?action=edit_$module&id=$id&error_message=$error_message");
        }
    }

    /**
     ***********
	@@@ ADD @@@
     ***********
     **/
} else if ($action == "add_$module") {

    if (empty($telecom_name) || $telecom_name == 'Please select') {
        $error_message = 'Telecom name is mandatory.';
    } else if (empty($package_name) || $package_name == 'Please select') {
        $error_message = 'Package name is mandatory.';
    } else if (empty($sim_number)) {
        $error_message = 'SIM Number is mandatory.';
    } else if (strlen($sim_number) < 11) {
        $error_message = 'SIM Number is not Valid.';
    } else {

        $amount = getTableAttr("amount", $tbl_prefix . 'sim_packages', $package_name);

        $insert_row = $mysqli->query("INSERT INTO `$tbl_name`(sim_number, telecom_name, package_name, amount, publish) VALUES ('" . $sim_number . "', '" . $telecom_name . "', '" . $package_name . "', '" . $amount . "', '" . $publish . "'); ");

        if ($insert_row) {
            $id = $mysqli->insert_id;
            fp__($tbl_name, $id);
            $success_message = "$module_caption Saved Successfully.";
            header("Location:listing_$module.php?success_message=$success_message");
            //////////////////////////////////////////////////
        } else {
            $error_message = "Sorry ! $module_caption could not Save.";
            // header("Location:$module.php?error_message=$error_message");
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

    $sim_number                  = stripslashes($row['sim_number']);
    $telecom_name                = stripslashes($row['telecom_name']);
    $package_name                = stripslashes($row['package_name']);
    // $amount                     = stripslashes($row['amount']);
    $publish                     = stripslashes($row['publish']);
}

///////////////////////////////////////////////////////////////////////////////
?>
<div class="content-wrapper">
    <div class="content">

        <?php include('admin_elements/breadcrumb.php'); ?>

        <form method="post" id="frm<?php echo $module; ?>" name="frm<?php echo $module; ?>" action="<?php echo $module; ?>.php" class="form-horizontal">
            <?php if (($action == "edit_$module" || $action == "update_$module") && !empty($id)) { ?>
                <input type="hidden" name="action" id="action" value="update_<?php echo $module; ?>" />
                <input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
            <?php } else { ?>
                <input type="hidden" name="action" id="action" value="add_<?php echo $module; ?>" />
            <?php } ?>

            <div class="row">

                <div class="col-md-8">
                    <div class="panel panel-flat">
                        <div class="panel-heading">

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="col-md-12">
                                        <h5><?php echo $module_caption; ?> Details </h5>
                                    </div>
                                </div>

                                <div class="col-md-9 text-right">
                                    <div class="col-md-4"></div>
                                    <!-- <div class="col-md-4"> -->
                                    <!-- <input type="checkbox" name="publish" id="publish" data-on-color="success" data-size="small" <?php if ($publish == '1') { ?>checked="checked" <?php } ?> /> -->
                                    <!-- </div> -->
                                    <div class="col-md-4">
                                        <a href="listing_<?php echo $module; ?>.php"><button type="button" class="btn btn-default"> Listing </button></a>
                                    </div>
                                    <div class="col-md-4">
                                        <button type="submit" class="btn btn-info"><?php if (($action == "edit_$module" || $action == "update_$module") && !empty($id)) { ?>Update<?php } else { ?>Save<?php } ?> </button>
                                    </div>
                                </div>

                            </div>

                            <div class="panel-body">

                                <div class="form-group">
                                    <label class="col-lg-3 control-label"><strong>Telecom Name</strong> <span class="f_req">*</span></label>
                                    <div class="col-lg-9">
                                        <select class="form-control" name="telecom_name" id="telecom_name">
                                            <option value=''>Please select</option>
                                            <option value="asiacell" <?php if ($telecom_name == 'asiacell') { ?>selected="selected" <?php } ?>>Asia Cell - 0770</option>
                                            <option value="zain" <?php if ($telecom_name == 'zain') { ?>selected="selected" <?php } ?>>Zain - 0780</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-lg-3 control-label"><strong>Package Name</strong> <span class="f_req">*</span></label>
                                    <div class="col-lg-9">
                                        <select class="form-control" name="package_name" id="package_name">
                                            <option value=''>Please select</option>
                                            <?php
                                            $result = $mysqli->query("SELECT * FROM `" . $tbl_prefix . "sim_packages` WHERE publish=1 ORDER BY package_name");
                                            while ($rows = $result->fetch_array()) {
                                            ?>
                                                <option value="<?php echo $rows['id']; ?>" <?php if ($action == "edit_$module" && $rows['id'] == $package_name) { ?>selected <?php } else if ($rows['id'] == $package_name) { ?>selected <?php } ?>>
                                                    <?php echo $rows['package_name']; ?> - <?php echo $rows['amount']; ?> (IQD)
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-lg-3 control-label"><strong>SIM Number</strong> <span class="f_req">*</span></label>
                                    <div class="col-lg-9">
                                        <input name="sim_number" id="sim_number" value="<?php echo $sim_number; ?>" class="form-control" type="text" onkeyup="javascript: char_count(this.id);" maxlength="11">
                                        <span class="help-block"><strong><span id="span_sim_number"><?php echo strlen($sim_number); ?></span></strong> &nbsp; - &nbsp; 11 characters (Format: 07738512345)</span>
                                    </div>
                                </div>

                                <!-- <div class="form-group">
                                    <label class="col-lg-3 control-label"><strong>amount (IQD)</strong> <span class="f_req">*</span></label>
                                    <div class="col-lg-9">
                                        <input name="amount" id="amount" value="<?php //echo $amount; 
                                                                                ?>" class="form-control" type="text">
                                    </div>
                                </div> -->

                            </div>

                            <!-- <div class="text-left">
						<button type="submit" class="btn btn-info"><?php if (($action == "edit_$module" || $action == "update_$module") && !empty($id)) { ?>Update<?php } else { ?>Save<?php } ?> <?php echo $module_caption; ?></button>
						<a href="listing_<?php echo $module; ?>.php"><button type="button" class="btn btn-default"> Back to <?php echo ucfirst($module_caption); ?> Listing </button></a>
					</div> -->

                        </div>
                    </div>

                </div>

            </div>
        </form>

        <?php include('admin_elements/copyright.php'); ?>
    </div>
</div>
<?php include('admin_elements/admin_footer.php'); ?>