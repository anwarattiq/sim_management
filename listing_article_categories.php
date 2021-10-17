<?php
	include('admin_elements/admin_header.php');
	$module = 'article_categories';
	$module_caption = 'Article Category';
	$tbl_name = $tbl_prefix.$module;
	$error_message = ''; $success_message = '';
	#########################################

	if (isset($_REQUEST['file_name'])){
		$file_name = $mysqli->real_escape_string(stripslashes($_REQUEST['file_name']));
	} else {
		$file_name = '';
	}

	/**
	**************************************
				 	   @@@ PUBLISH @@@
	**************************************
	**/
	if ( ($action=="publish_$module" && !empty($id))){

		if ( publish($module_caption, $tbl_name, $id) )
			$success_message = "$module_caption Published Successfully.";
		else
			$error_message = "Sorry! $module Could Not Be Published.";

	/**
	**************************************
				 	   @@@ PUBLISH @@@
	**************************************
	**/
	} else if ( ($action=="unpublish_$module" && !empty($id))){

		if ( unpublish($module_caption, $tbl_name, $id) )
			$success_message = "$module_caption Un-Published Successfully.";
		else
			$error_message = "Sorry! $module Could Not Be Un-Published.";


	/**
	**************************************
	@@@ DELETE @@@
	**************************************
	**/
	} else if ( ($action=="delete_$module" && !empty($id))){

		$result = 0;
		//SUPERADMIN CAN DELETE ANY DATA
		if ($_SESSION[$project_pre]['type']=='superadmin'){

			$cascade   = $mysqli->query("SELECT id FROM `".$GLOBALS['TBL']['PREFIX']."articles` WHERE category=$id");
			if ($cascade->num_rows > 0){
				$error_message = "Category associated with rows in Articles Table. ";

			} else {
				$result = $mysqli->query("DELETE FROM `$tbl_name` WHERE id=$id");
			}

		//ADMIN CAN DELETE ONLY HIS/HER DATA
		} else {
			$cascade   = $mysqli->query("SELECT id FROM `".$GLOBALS['TBL']['PREFIX']."articles` WHERE category=$id");
			if ($cascade->num_rows > 0){
				$error_message = "Category associated with rows in Articles Table.";

			} else {
				$result = $mysqli->query("DELETE FROM `$tbl_name` WHERE id=$id AND created_by='".$_SESSION[$project_pre]['admin_id']."'");
			}

		}


		if($result){
			$success_message = "$module_caption Deleted Successfully.";
			header("Location:listing_$module.php?page=$page&success_message=$success_message");
		} else {
			$error_message .= "Sorry! $module Could Not Be Deleted.";
		}
	}
	///////////////////////////////////////////////////////////////////////////////
?>
<div class="content-wrapper">
	<div class="page-header page-header-default">
		<div class="page-header-content">
			<div class="page-title">
				<h4><i class="icon-circle-right2 position-left"></i> Listing <?php echo '<strong>'.ucwords($type).'</strong>';?> <?php echo $module_caption;?></h4>
				<a class="heading-elements-toggle"><i class="icon-more"></i></a>
			</div>
			<div class="heading-elements">
				<div class="heading-btn-group">
					<a href="<?php echo $module;?>.php"><button class="btn btn-info">Add <?php echo $module_caption;?></button></a>
				</div>
			</div>
		</div>
	</div>
	<div class="content">

		<?php include('admin_elements/breadcrumb.php');?>

		<div class="row">
			<div class="col-md-12">

		        <!-- <div class="text-center"><a href="<?php echo $module?>.php"><button class="btn btn-info">Add <?php echo $module_caption;?></button></a></div> -->

		        <table id="grid-<?php echo $module;?>" class="display responsive no-wrap table table-striped table-bordered table-hover">
		            <thead>
		            <tr>
									<th width="40">Manage</th>
									<th>Category</th>
									<th>Articles</th>
		            </tr>
		          </thead>
		        </table>

			</div>
		</div>

		<div class="alert alert-info alert-styled-left alert-arrow-left alert-component">
				<!-- <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button> -->
				<ul>
					<li><strong>Un-Published Article Categories</strong> will not be displayed on <strong>Front-end Popular Categories</strong> but articles within these categories will be LIVE.</li>
					<li><strong>ID:1</strong> -> Page. Cannot delete or edit.</li>
				</h6>
    </div>

		<?php include('admin_elements/copyright.php');?>
	</div>
</div>
<?php include('admin_elements/admin_footer.php');?>
