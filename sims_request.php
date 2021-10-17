	<?php
	include('admin_elements/admin_header.php');
include('classes/CI_Upload.php');
		
	$module = 'sims_request';
	$module_caption = 'Sim Request Form';
	$tbl_name = $tbl_prefix . $module;
	$error_message = '';
	$success_message = '';
	$company=0;
	#########################################
	if (isset($_POST['publish'])) 				$publish = 1;
	else $publish = 0;

	/**
	 **************************************
	@@@ GET ALL VARIABLES ADD/UPDATE @@@
	 **************************************
	 **/
	if ($action == "update_$module" || $action == "add_$module") {
		$company					= $mysqli->real_escape_string(stripslashes($_POST['company']));
		
		$quantity					= $mysqli->real_escape_string(stripslashes($_POST['quantity']));
		$telecom_name					= $mysqli->real_escape_string(stripslashes($_POST['telecom_name']));
	} else {
		$company					= '';
		
		$quantity					= '';
		$telecom_name					= '';
		$file=                   '';
	}


	/**
	 **************************************
	@@@ UPDATE @@@
	 **************************************
	 **/
	if ($action == "update_$module" && !empty($id)) {

		if (empty($company) || $company == 'Please select') {
			$error_message = 'Company is mandatory.';
		} else if (empty($quantity)) {
			$error_message = 'quantity  is mandatory.';
		
		} else if (empty($_FILES['file']['name'])) {
			print_r($_FILES);
			$error_message = 'File is mandatory.';
		} else {
$file_name='';
	$ci=new CI_Upload();
	
		$config['allowed_types'] = '';

if(!empty($_FILES['file']['name'])){
				
//$this->load->library('upload');   
        $config['upload_path'] = './uploads/sims_request/';
        $config['allowed_types'] = 'pdf';
        $config['max_size']    = '1000000';
    

        $ci->initialize($config);
        $certificateflag = $ci->do_upload($_FILES['file']['name']);       
        if ($ci->do_upload("file"))
            error_reporting(E_ALL);
        else{
            echo "<pre>"; Print_r($ci->data()); echo "</pre>";
        }
		$file_name=$ci->data()['file_name'];
	
}
 
			//////////////////////////////////////////////////
			$update_row = $mysqli->query("
										UPDATE `$tbl_name` SET
										company							= '" . $company . "',
										
										quantity						= '" . $quantity . "',
										telecom_name							= '" . $telecom_name . "',
										file							= '" . $file_name . "'
										
										WHERE id=$id");
			if ($update_row) {
				$success_message = "$module_caption Updated Successfully.";
				fp__($tbl_name, $id);
				header("Location:listing_$module.php?success_message=$success_message");
			} else {
				$error_message = "Sorry ! $module_caption Could Not Be Updated.";
				//header("Location:$module.php?action=edit_$module&id=$id&error_message=$error_message");
			}
		} }

		/**
		 **************************************
	@@@ ADD @@@
		 **************************************
		 **/
	 else if ($action == "add_$module") {

		if (empty($company) || $company == 'Please select') {
			$error_message = 'Company Name is mandatory.';
		} else if (empty($quantity)) {
			$error_message = 'quantity is mandatory.';
		} else if (empty($telecom_name)) {
			$error_message = 'telecom is mandatory.';
		} else {
			
	$file_name='';
	$ci=new CI_Upload();
	
		$config['allowed_types'] = 'pdf';

if(!empty($_FILES['file']['name'])){
				
//$this->load->library('upload');   
        $config['upload_path'] = './uploads/sims_request/';
     $config['allowed_types'] = 'pdf';
       
        $ci->initialize($config);
        $certificateflag = $ci->do_upload($_FILES['file']['name']);  
     
        if ($ci->do_upload("file")){
            
            	$file_name=$ci->data()['file_name'];

        }		
           
            
        else{
            
           	$error_message = "Sorry ! $module_caption Could Not Be Saved.Only PDF";
         	header("Location:$module.php?error_message=$error_message");exit;
        }
	
	
}

	$user_id=$_SESSION['MT']['admin_id'];
		$insert_row = $mysqli->query("INSERT INTO `$tbl_name`(company, telecom_name, quantity,file,user_id) VALUES ('" . $company . "','" . $telecom_name . "',  '" . $quantity . "',  '" . $file_name . "','" . $user_id . "'); ");

			if ($insert_row) {
				$id = $mysqli->insert_id;
				$success_message = "$module_caption Saved Successfully.";
				header("Location:listing_$module.php?success_message=$success_message");
				//////////////////////////////////////////////////
			} else {
				$error_message = "Sorry ! $module_caption Could Not Be Saved.";
				//header("Location:$module.php?error_message=$error_message");
			}
		}
	}


	/**
	 **************************************
	@@@ EDIT @@@
	 **************************************
	 **/

	if (!empty($id)) {

		$result = $mysqli->query("SELECT * FROM `$tbl_name` WHERE id=$id");
		$row = $result->fetch_array();

		$company							= stripslashes($row['company']);
		$quantity							= stripslashes($row['quantity']);
		$file						= stripslashes($row['file']);
		$telecom_name								= stripslashes($row['telecom_name']);
		
		
	}

	///////////////////////////////////////////////////////////////////////////////
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
											<h5><?php echo $module_caption; ?> Details <?php if (!empty($slug)) { ?><a href="<?php echo $base_url; ?>/article/<?php echo $slug; ?>" target="_blank">&#128065;</a><?php } ?></h5>
										</div>
									</div>

									<div class="col-md-9 text-right">
										
										<div class="col-md-4">
											<a href="listing_<?php echo $module; ?>.php"><button type="button" class="btn btn-default">Listing</button></a>
										</div>
										<div class="col-md-4">
											<button type="submit" class="btn btn-info"><?php if (($action == "edit_$module" || $action == "update_$module") && !empty($id)) { ?>Update<?php } else { ?>Save<?php } ?></button>
										</div>

									</div>

								</div>

								<div class="panel-body">
<?php if($_SESSION['MT']['type']=='superadmin'){?>
									
<div class="form-group">
										<label class="col-lg-3 control-label"><strong>Company Name</strong><span class="f_req">*</span></label>
										<div class="col-lg-9">
											<select class="form-control" name="company" id="company">
												<option value=''>Please select</option>
												<?php
												$result = $mysqli->query("SELECT * FROM `" . $tbl_prefix . "companies` WHERE publish=1 ORDER BY company_name");
												while ($rows = $result->fetch_array()) {
												?>
													<option value="<?php echo $rows['id']; ?>" <?php if ($action == "edit_$module" && $rows['id'] == $company) { ?>selected <?php } else if ($rows['id'] == $company) { ?>selected <?php } ?>>
														<?php echo $rows['company_name']; ?>
													</option>
												<?php } ?>
											</select>
										</div>
									</div>
							
<?php }else { echo '<input type="hidden" id="company" name="company" value='.$_SESSION['MT']['company_id'].' />'; } ?>							
									
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
										<label class="col-lg-3 control-label"><strong>Quantity</strong><span class="f_req">*</span></label>
										<div class="col-lg-9">
											<select class="form-control" name="quantity" id="quantity">
												<option value="0">Please select</option>
												<?php
												$str='';
												for($i=1;$i<=99;$i++){
												    if($i==$company){
												        $str="selected";
												    }else $str='';
												?>
													<option value="<?php echo $i; ?>" <?php echo $str;?>>
														<?php echo $i; ?>
													</option>
												<?php } ?>
											</select>
										</div>
									</div>

									<div class="form-group">
										<label class="col-lg-3 control-label"><strong>Attached Request file</strong></label>
										<div class="col-lg-9">
											<input type="file" class="sepH_a form-control" id="file" name="file"  required="required"/><?php //echo $file; ?><b>Only PDF*</b>
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