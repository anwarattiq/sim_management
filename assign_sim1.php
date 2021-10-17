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
    $site_title                     = $mysqli->real_escape_string(stripslashes($_POST['site_title']));
    $site_url                       = $mysqli->real_escape_string(stripslashes($_POST['site_url']));
    $meta_title                     = $mysqli->real_escape_string(stripslashes($_POST['meta_title']));
    $meta_description               = $mysqli->real_escape_string(stripslashes($_POST['meta_description']));
} else {
    $sim_number                     = '';
    $site_title                     = '';
    $site_url                       = '';
    $meta_title                     = '';
    $meta_description               = '';
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

        // PROCESS MULTIPLE SIM Numbers
        $sim_numbers_ids = '';
        if ($sim_number) {

            $sim_numbers = $_POST['sim_number'];
            if (!empty($sim_numbers)) {

                foreach ($sim_numbers as $sim_number_id) {
                    $sim_numbers_ids .= $sim_number_id . ',';
                } //foreach

                $sim_numbers_ids = substr($sim_numbers_ids, 0, -1);
            }
        }

        $created        = processDateDtoY($created);
        $created        = $created . ' 00:00:00';;

        $update_row = $mysqli->query("
				UPDATE `$tbl_name` SET
					sim_number						= '" . $sim_numbers_ids . "',
					slug							= '" . $slug . "',
					article_title					= '" . $article_title . "',
					article_description				= '" . $article_description . "',
					meta_title						= '" . $meta_title . "',
					meta_description				= '" . $meta_description . "',
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

    if (empty($sim_number) || $sim_number == 'Please select') {
        $error_message = 'SIM Number is mandatory.';
    } else {

        // PROCESS MULTIPLE sim_numbers
        $sim_numbers_ids = '';
        if ($sim_number) {

            $sim_numbers = $_POST['sim_number'];
            if (!empty($sim_numbers)) {

                foreach ($sim_numbers as $sim_number_id) {
                    $sim_numbers_ids .= $sim_number_id . ',';
                } //foreach

                $sim_numbers_ids = substr($sim_numbers_ids, 0, -1);
            }
        }

        $created        = processDateDtoY($created);
        $created        = $created . ' 00:00:00';

        echo $created;

        $insert_row = $mysqli->query("INSERT INTO `$tbl_name`(sim_number, slug, bit, article_title, article_description, meta_title, meta_description, created, publish) VALUES ('" . $sim_numbers_ids . "', '" . $slug . "', '" . $bit . "', '" . $article_title . "', '" . $article_description . "', '" . $meta_title . "', '" . $meta_description . "', '" . $created . "', '" . $publish . "'); ");

        if ($insert_row) {
            $id = $mysqli->insert_id;
            fp__($tbl_name, $id);
            $success_message = "$module_caption Saved Successfully.";
            header("Location:listing_$module.php?success_message=$success_message");
            //////////////////////////////////////////////////
        } else {
            $error_message .= "Sorry ! $module_caption Could Not Be Saved.";
            //header("Location:$module.php?error_message=$error_message");
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
    $sim_number_arr              = explode(',', $sim_number);
    $created                     = stripslashes($row['created']);
    $created                     = substr($created, 0, -9); // . ' 00:00:00';
    $created                     = processDateYtoD($created);
    $publish                     = stripslashes($row['publish']);
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
                <form method="post" id="frm<?php echo $module; ?>" name="frm<?php echo $module; ?>" action="<?php echo $module; ?>.php" enctype="multipart/form-data">
                    <input type="hidden" name="action" id="action" value="update_<?php echo $module; ?>" />

                    <div class="row">

                        <div class="col-md-4">
                            <div class="panel panel-flat">
                                <div class="panel-heading">

                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="col-md-12">
                                                <h2><?php echo getTableAttr("employee_name", $tbl_prefix . 'employees', $id); ?> </h2>
                                            </div>
                                        </div>


                                    </div>

                                </div>

                                <div class="panel-body">

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label><strong>Sitemap Root</strong> <span class="f_req">*</span></label>
                                                <input name="sitemap_root" id="sitemap_root" value="<?php echo $sitemap_root; ?>" class="form-control" type="text">
                                                <span class="help-block">default: sitemap_mast.xml</span>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>

                        <div class="col-md-4">
                            <div class="panel panel-flat">
                                <div class="panel-heading">

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-9">
                                                <h5>Please select Available SIMs:</h5>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="text-left">
                                                    <button type="submit" class="btn btn-info">Update</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="panel-body">
                                    <div class="alert alert-info">
                                        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                                        Assign to <span class="text-bold"><?php echo getTableAttr("employee_name", $tbl_prefix . 'employees', $id); ?></span>
                                    </div>

                                    <div class="form-group">
                                        <label><strong>Start Date</strong> <span class="f_req">*</span></label>
                                        <input name="site_url" id="site_url" value="<?php echo $site_url; ?>" class="form-control" type="text">
                                        <span class="help-block">keep it without ssl and with www: http://www.example.com</span>
                                    </div>

                                    <div>
                                        <hr />
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label><strong>Available SIMs</strong> <span class="f_req">*</span></label>
                                                <select class="form-control" name="sim_number[]" id="sim_number[]" multiple="multiple" style="height:400px;">
                                                    <?php if (($action == "add_$module") || empty($id)) { ?>
                                                        <option value='1' selected>Uncategorized</option>
                                                    <?php } ?>
                                                    <?php
                                                    $result = $mysqli->query("SELECT * FROM `" . $tbl_prefix . "sims` WHERE publish>-1 ORDER BY sim_number");
                                                    while ($rows = $result->fetch_array()) {
                                                    ?>
                                                        <option value="<?php echo $rows['id']; ?>" <?php if ($action == "edit_$module" && in_array($rows['id'], $sim_number_arr)) { ?>selected <?php } else if ($rows['id'] == $sim_number) { ?>selected <?php } ?>>
                                                            <?php echo $rows['sim_number']; ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>


                                </div>

                            </div>
                        </div>


                        <div class="col-md-4">
                            <div class="panel panel-flat">
                                <div class="panel-heading">

                                    <div class="row">
                                        <div class="col-md-7">
                                            <div class="col-md-12">
                                                <h5>Reports</h5>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="panel-body">

                                    <div class="form-group">
                                        <!-- <label><strong>AMP Logo</strong> [<?php echo $logo_amp_width; ?>px x <?php echo $logo_amp_width; ?>]</label> -->

                                        <span class="help-block">Google reports.</span>
                                    </div>

                                    <hr />

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