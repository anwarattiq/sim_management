<?php
include('admin_elements/admin_header.php');
$module = 'cities';
$module_caption = 'City';
$tbl_name = $tbl_prefix . $module;
$error_message = '';
$success_message = '';
#########################################

if (isset($_POST['publish']))                 $publish = 1;
else $publish = 0;;


/**
 ************************************
@@@ GET ALL VARIABLES ADD/UPDATE @@@
 ************************************
 **/
if ($action == "update_$module" || $action == "add_$module") {
    $city_name                = $mysqli->real_escape_string(stripslashes($_POST['city_name']));
} else {
    $city_name                = '';
}

/**
 **************
@@@ UPDATE @@@
 **************
 **/
if ($action == "update_$module" && !empty($id)) {

    if (empty($city_name)) {
        $error_message = 'City Name is mandatory.';
    } else if (checkDuplicateRow($tbl_name, 'city_name', $city_name)) {
        $error_message = 'Duplicate City Name. Please enter different.';
    } else {

        //////////////////////////////////////////////////
        $update_row = $mysqli->query("
								UPDATE `$tbl_name` SET
									city_name	= '" . $city_name . "',
									publish			= '" . $publish . "'
								WHERE id=$id");
        if ($update_row) {
            $success_message = "$module_caption Updated Successfully.";
            fp__($tbl_name, $id);
            header("Location:listing_$module.php?success_message=$success_message");
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

    if (empty($city_name)) {
        $error_message = 'City Name is mandatory.';
    } else if (checkDuplicateRow($tbl_name, 'city_name', $city_name)) {
        $error_message = 'Duplicate City Name. Please enter different.';
    } else {

        $insert_row = $mysqli->query("INSERT INTO `$tbl_name`(city_name, publish) VALUES ('" . $city_name . "', '" . $publish . "'); ");

        if ($insert_row) {
            $id = $mysqli->insert_id;
            $success_message = "$module_caption Saved Successfully.";
            header("Location:listing_$module.php?success_message=$success_message");
            //////////////////////////////////////////////////
        } else {
            $error_message = "Sorry ! $module_caption Could Not Be Saved.";
            header("Location:$module.php?error_message=$error_message");
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

    $city_name        = stripslashes($row['city_name']);
    $publish         = stripslashes($row['publish']);
}
////////////////////////////////////////////////
?>
<div class="content-wrapper">
    <div class="content">

        <?php include('admin_elements/breadcrumb.php'); ?>

        <form method="post" id="frm<?php echo $module; ?>" name="frm<?php echo $module; ?>" action="<?php echo $module; ?>.php" enctype="multipart/form-data" class="form-horizontal">
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
                                        <h5><?php echo $module_caption; ?> </h5>
                                    </div>
                                </div>

                                <div class="col-md-9 text-right">
                                    <div class="col-md-10">
                                        <input type="checkbox" name="publish" id="publish" data-on-color="success" data-size="small" <?php if ($publish == '1') { ?>checked="checked" <?php } ?> />
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-info"><?php if (($action == "edit_$module" || $action == "update_$module") && !empty($id)) { ?>Update<?php } else { ?>Save<?php } ?></button>
                                    </div>
                                </div>

                            </div>

                            <div class="panel-body">

                                <div class="form-group">
                                    <label class="col-lg-3 control-label"><strong>City Name</strong> <span class="f_req">*</span></label>
                                    <div class="col-lg-9">
                                        <input name="city_name" id="city_name" value="<?php echo $city_name; ?>" class="form-control" type="text">
                                    </div>
                                </div>

                            </div>


                        </div>
                    </div>

                </div>


                <!-- <div class="col-md-6">
					<div class="panel panel-flat">
						<div class="panel-heading">

							<div class="panel-body">

							</div>

						</div>
					</div>
				</div> -->


            </div>
        </form>

        <?php include('admin_elements/copyright.php'); ?>
    </div>
</div>
<?php include('admin_elements/admin_footer.php'); ?>