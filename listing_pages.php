<?php
	include('admin_elements/admin_header.php');
	$module = 'pages';
	$module_caption = 'Page';
	$tbl_name = $tbl_prefix.$module;
	$error_message = ''; $success_message = '';
	#########################################

	/**
	*************
	@@@ CACHE @@@
	*************
	**/
	if ( ($action=="cache_$module" && !empty($id))){

		$result = $mysqli->query("UPDATE `$tbl_name` SET cache=1 WHERE id=$id");
		if ($result)
			$success_message = "$module_caption set cache Successfully.";
		else
			$error_message = "Sorry! $module Could Not Be set cache.";

	/**
	****************
	@@@ NO CACHE @@@
	****************
	**/
	} else if ( ($action=="nocache_$module" && !empty($id))){

		$result = $mysqli->query("UPDATE `$tbl_name` SET cache=0 WHERE id=$id");
		if ($result)
			$success_message = "$module_caption set nocache Successfully.";
		else
			$error_message = "Sorry! $module Could Not Be set nocache.";

	/**
	*****************
	@@@ MAIN MENU @@@
	*****************
	**/
	} else if ( ($action=="mainmenu_$module" && !empty($id))){

		$result = $mysqli->query("UPDATE `$tbl_name` SET main_menu=1 WHERE id=$id");
		if ($result)
			$success_message = "$module_caption set main menu Successfully.";
		else
			$error_message = "Sorry! $module Could Not Be set main menu.";

	/**
	********************
	@@@ NO MAIN MENU @@@
	********************
	**/
	} else if ( ($action=="nomainmenu_$module" && !empty($id))){

		$result = $mysqli->query("UPDATE `$tbl_name` SET main_menu=0 WHERE id=$id");
		if ($result)
			$success_message = "$module_caption set nomain menu Successfully.";
		else
			$error_message = "Sorry! $module Could Not Be set nomain menu.";

	/**
	***************
	@@@ PUBLISH @@@
	***************
	**/
	} else if ( ($action=="publish_$module" && !empty($id))){

		if ( publish($module_caption, $tbl_name, $id) )
			$success_message = "$module_caption Published Successfully.";
		else
			$error_message = "Sorry! $module Could Not Be Published.";

	/**
	***************
	@@@ PUBLISH @@@
	***************
	**/
	} else if ( ($action=="unpublish_$module" && !empty($id))){

		if ( unpublish($module_caption, $tbl_name, $id) )
			$success_message = "$module_caption Un-Published Successfully.";
		else
			$error_message = "Sorry! $module Could Not Be Un-Published.";


	/**
	**************
	@@@ DELETE @@@
	**************
	**/
	} else if ( ($action=="delete_$module" && !empty($id))){

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
									<th>Manage</th>
									<th width="60">php_page</th>
									<th width="250">Menu</th>
		              <th width="300">Meta Title</th>
		              <th>Meta Description</th>
									<th></th>
									<th>Main Menu</th>
		            </tr>
		          </thead>
		        </table>

			</div>
		</div>

		<div class="alert alert-info alert-styled-left alert-arrow-left alert-component">
			<ul>
				<li><strong>php_page:1</strong> .php page is mandatory with same slug.</li>
    </div>

		<?php include('admin_elements/copyright.php');?>
	</div>
</div>
<?php include('admin_elements/admin_footer.php');?>
