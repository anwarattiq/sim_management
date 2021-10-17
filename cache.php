<?php
	include('admin_elements/admin_header.php');
	$module = 'settings';
	$module_caption = 'Settings';
	$tbl_name = $tbl_prefix.$module;
	$photo_upload_path = '../uploads/'.$module.'/';
	$allowed_file_size = $GLOBALS['PHOTO']['MAX_UPLOAD_SIZE']; //MB Bytes
	$error_message = ''; $success_message = '';
	#########################################
	if (isset($_POST['publish'])) 			$publish = 1; 		else $publish = 0;
	/**
	**************************************
   @@@ GET ALL VARIABLES ADD/UPDATE @@@
	**************************************
	**/
	if ($action=="update_$module"){
    $facebook 			= $mysqli->real_escape_string(stripslashes($_POST['facebook']));
		$instagram 			= $mysqli->real_escape_string(stripslashes($_POST['instagram']));
		$linkedin 			= $mysqli->real_escape_string(stripslashes($_POST['linkedin']));

    $email 				  = $mysqli->real_escape_string(stripslashes($_POST['email']));
    $email_link	    = $mysqli->real_escape_string(stripslashes($_POST['email_link']));
		$phone          = $mysqli->real_escape_string(stripslashes($_POST['phone']));
		$phone_link     = $mysqli->real_escape_string(stripslashes($_POST['phone_link']));
		$whatsapp				= $mysqli->real_escape_string(stripslashes($_POST['whatsapp']));
		$whatsapp_link	= $mysqli->real_escape_string(stripslashes($_POST['whatsapp_link']));
	}

	/**
	**************************************
		 	  	@@@ DELETE PHOTO ONLY @@@
	**************************************
	**/
	if ( isset($_REQUEST['delete_photo']) && $_REQUEST['delete_photo']==1 ){
			$photo = getPhotoName(1, $tbl_name);
			if (!empty($photo)){
				delete_photo($photo, $photo_upload_path, '1'); 	// DELETE OLD THUMB
				delete_photo($photo, $photo_upload_path, '0');		// DELETE OLD PHOTO

				$result = $mysqli->query("UPDATE `$tbl_name` SET photo='' WHERE id=1");
				$success_message = 'Image Deleted Successfully.';
			}
	}

	/**
	**************************************
							@@@ UPDATE @@@
	**************************************
	**/
	if ($action=="update_$module"){

							/////// UPLOAD PHOTO ////////
							$photo = $_FILES["photo"]["name"];
							if (!empty($photo)){
                delete_photo($photo, $photo_upload_path, '1'); 	// DELETE OLD THUMB
                delete_photo($photo, $photo_upload_path, '0');	// DELETE OLD PHOTO

                $old_photo = getPhotoName(1, $tbl_name);
								$renamed_photo = renamePhoto($photo, $complete=0);
								$message = uploadPhoto('photo', $renamed_photo, $photo_upload_path, $allowed_file_size, $old_photo, '200', '150');
								if ($message)	$error_message = $message;
								else					$result = $mysqli->query("UPDATE `$tbl_name` SET photo='$renamed_photo' WHERE id=1");
        			}

							//////////////////////////////////////////////////
							//Update Query
							$update_row = $mysqli->query("
															UPDATE `$tbl_name` SET
                                facebook 			= '".$facebook."',
																instagram 		= '".$instagram."',
																linkedin 			= '".$linkedin."',

                                email       	= '".$email."',
                                email_link    = '".$email_link."',
																phone         = '".$phone."',
																phone_link    = '".$phone_link."',
																whatsapp      = '".$whatsapp."',
																whatsapp_link = '".$whatsapp_link."'
															WHERE id=1");
							if($update_row){
								$success_message = "$module_caption Updated Successfully.";
								fp__($tbl_name, 1);
								//header("Location:listing_$module.php?success_message=$success_message");
							} else {
								$error_message = "Sorry ! $module_caption Could Not Be Updated.";
								//header("Location:$module.php?action=edit_$module&id=1&error_message=$error_message");
							}
	}

  	/**
  	**************************************
  					 	   @@@ EDIT @@@
  	**************************************
  	**/

		$result = $mysqli->query("SELECT * FROM `$tbl_name` WHERE id=1");
		$row = $result->fetch_array();

      $facebook 		= stripslashes($row['facebook']);
			$instagram 		= stripslashes($row['instagram']);
			$linkedin 		= stripslashes($row['linkedin']);

      $email 			  = stripslashes($row['email']);
      $email_link   = stripslashes($row['email_link']);
			$phone        = stripslashes($row['phone']);
			$phone_link   = stripslashes($row['phone_link']);
			$whatsapp			= stripslashes($row['whatsapp']);
			$whatsapp_link= stripslashes($row['whatsapp_link']);

	$photo = getPhotoName(1, $tbl_name);
	///////////////////////////////////////////////////////////////////////////////
?>
<div id="contentwrapper">
  <div class="main_content">
    <?php include('admin_elements/breadcrumb.php');?>
    <?php include('admin_elements/errors.php');?>

    <form method="post" id="frm<?php echo $module;?>" name="frm<?php echo $module;?>" action="<?php echo $module;?>.php" enctype="multipart/form-data">
    <input type="hidden" name="action" id="action" value="update_<?php echo $module;?>" />
    <input type="hidden" name="id" id="id" value="<?php echo 1;?>" />

    <div class="row">
      <div class="col-sm-6 col-md-6">
      <h3 class="heading"><?php echo $module_caption;?> Details</h3>

        <div class="formSep">

          <div class="row">
            <div class="col-sm-6 col-md-6">
              <label>Facebook</label>
              <input type="text" name="facebook" id="facebook" value="<?php echo $facebook;?>" class="form-control" />
            </div>
          </div>

          <div class="row">
            <div class="col-sm-6 col-md-6">
              <label>Instagram</label>
              <input type="text" name="instagram" id="instagram" value="<?php echo $instagram;?>" class="form-control" />
              <!-- <span class="help-block">e.g. http://www.example.com/</span> -->
            </div>
          </div>

          <div class="row">
            <div class="col-sm-6 col-md-6">
              <label>Linkedin</label>
              <input type="text" name="linkedin" id="linkedin" value="<?php echo $linkedin;?>" class="form-control" />
            </div>
          </div>
        </div>

        <div class="formSep">

          <div class="row">
            <div class="col-sm-6 col-md-6">
              <label>Email Text</label>
              <input type="text" name="email" id="email" value="<?php echo $email;?>" class="form-control" />
            </div>

            <div class="col-sm-6 col-md-6">
              <label>Email Link</label>
              <input type="text" name="email_link" id="email_link" value="<?php echo $email_link;?>" class="form-control" />
            </div>
          </div>

          <div class="row">
            <div class="col-sm-6 col-md-6">
              <label>Phone Text</label>
              <input type="text" name="phone" id="phone" value="<?php echo $phone;?>" class="form-control" />
            </div>

            <div class="col-sm-6 col-md-6">
              <label>Phone Link</label>
              <input type="text" name="phone_link" id="phone_link" value="<?php echo $phone_link;?>" class="form-control" />
            </div>
          </div>

          <div class="row">
            <div class="col-sm-6 col-md-6">
              <label>WhatsApp Text</label>
              <input type="text" name="whatsapp" id="whatsapp" value="<?php echo $whatsapp;?>" class="form-control" />
            </div>

            <div class="col-sm-6 col-md-6">
              <label>WhatsApp Link</label>
              <input type="text" name="whatsapp_link" id="whatsapp_link" value="<?php echo $whatsapp_link;?>" class="form-control" />
            </div>
          </div>

        </div>

        <div class="form-actions">
          <button class="btn btn-gebo" type="submit">Update <?php echo $module_caption;?></button>
        </div>
      </div>

      <div class="col-sm-6 col-md-6">
        <h3 class="heading">&nbsp;</h3>
        <div class="row">
          <div class="col-sm-6 col-md-6">Website Icon [320px x 100px]
            <div class="fileupload fileupload-new" data-provides="fileupload">
              <div class="fileupload-new img-thumbnail sepH_a" style="width: 178px; height: 120px">
                <img src="<?php echo $photo_upload_path.$photo?>" alt="" width="170" height="110" />
              </div>

              <div class="fileupload-preview fileupload-exists img-thumbnail sepH_a" style="width: 178px; height: 120px"></div>
              <div>
                <span class="btn btn-default btn-file">
                  <span class="fileupload-new">Select image</span>
                  <span class="fileupload-exists">Change</span>
                  <input type="file" name="photo" id="photo" />
                </span>
                <a href="<?php echo $module;?>.php?action=<?php echo $action;?>&id=1&delete_photo=1';"><button type="button" class="btn btn-default">Remove</button></a>

              </div>
            </div>
          </div>
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
