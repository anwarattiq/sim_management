<?php
	include('admin_elements/admin_header.php');
	$module = 'pages';
	$module_caption = 'Content Page';
	$tbl_name = $tbl_prefix.$module;
	$error_message = ''; $success_message = '';
	#########################################
	if (isset($_POST['publish'])) 			$publish = 1; 		else $publish = 0;

	/**
	**************************************
   @@@ GET ALL VARIABLES ADD/UPDATE @@@
	**************************************
	**/
	if ($action=="update_$module" || $action=="add_$module"){
		$heading 						= $mysqli->real_escape_string(stripslashes($_POST['heading']));
		$subheading			 	  = $mysqli->real_escape_string(stripslashes($_POST['subheading']));
		$page_description 	= $mysqli->real_escape_string(stripslashes($_POST['page_description']));

	} else {
		$heading 						= '';
		$subheading			 	  = '';
		$page_description 	= '';
	}

	/**
	**************************************
							@@@ UPDATE @@@
	**************************************
	**/
	if ($action=="update_$module" && !empty($id)){

			if (empty($heading)){
				$error_message = 'Heading is mandatory.';

			} else if (empty($page_description)){
				$error_message = 'Page Description is mandatory.';

			} else {
							//Update Query
							$update_row = $mysqli->query("
															UPDATE `$tbl_name` SET
																heading 					= '".$heading."',
																subheading 			  = '".$subheading."',
																page_description 	= '".$page_description."',
																publish 					= '".$publish."'
															WHERE id=$id");
							if($update_row){
								$success_message = "$module_caption Updated Successfully.";
								fp__($tbl_name, $id);
								//header("Location:listing_$module.php?success_message=$success_message");
							} else {
								$error_message = "Sorry ! $module_caption Could Not Be Updated.";
								//header("Location:$module.php?action=edit_$module&id=$id&error_message=$error_message");
							}
			}
	}

	/**
	**************************************
					 	   @@@ EDIT @@@
	**************************************
	**/
	if ( !empty($id) ){

			$result = $mysqli->query("SELECT * FROM `$tbl_name` WHERE id=$id");
			$row = $result->fetch_array();

				$heading 					= stripslashes($row['heading']);
				$subheading		 	  = stripslashes($row['subheading']);
				$page_description = stripslashes($row['page_description']);
				$publish 					= stripslashes($row['publish']);
	}

	///////////////////////////////////////////////////////////////////////////////
?>
  <div id="contentwrapper">
    <div class="main_content">
  		<?php include('admin_elements/breadcrumb.php');?>
			<?php include('admin_elements/errors.php');?>

      <form method="post" id="frm<?php echo $module;?>" name="frm<?php echo $module;?>" action="content.php">
      <input type="hidden" name="action" id="action" value="update_<?php echo $module;?>" />
      <input type="hidden" name="id" id="id" value="<?php echo $id;?>" />

			<div class="row">
      	<div class="col-sm-6 col-md-6">
        <h3 class="heading"><?php echo $module_caption;?> Details</h3>

          <div class="formSep">

            <div class="row">
              <div class="col-sm-6 col-md-6">
	              <label>Heading <span class="f_req">*</span></label>
                <input name="heading" id="heading" value="<?php echo $heading;?>" class="form-control" type="text">
              </div>
            </div>

            <div class="row">
              <div class="col-sm-6 col-md-6">
	              <label>Subheading <span class="f_req">*</span></label>
                <input name="subheading" id="subheading" value="<?php echo $subheading;?>" class="form-control" type="text">
              </div>
            </div>

            <div class="row">
              <div class="col-sm-12 col-md-12">
                <label>Page Description <span class="f_req">*</span></label>
								<textarea class="sepH_a form-control" rows="4" cols="1" id="page_description" name="page_description"><?php echo $page_description;?></textarea>
              </div>
            </div>

            <div class="row">
              <div class="col-sm-2 col-md-2">
                <label>Publish</label>
                <input type="checkbox" name="publish" id="publish" data-on-color="success" data-size="small" <?php if ($publish=='1'){?>checked="checked"<?php }?> />
              </div>
            </div>


          </div>

          <div class="form-actions">
            <button class="btn btn-gebo" type="submit">Update <?php echo $module_caption;?></button>
          </div>
      	</div>

      </div>
      </form>
    </div>
    <!--END CONTENT-->
  </div>
</div>
<?php include('admin_elements/sidebar.php');?>
<?php include('admin_elements/admin_footer.php');?>
