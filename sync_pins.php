<?php
	include('admin_elements/admin_header.php');
	$module 					= 'companies_active';
	$module_caption 	= 'Sync Pins';
	$tbl_name 				= $tbl_prefix.$module;
	$error_message 		= '';
	$success_message 	= '';
	#########################################

	$uploaded_pins = scandir('../pins/');

	array_shift ($uploaded_pins); // remove . from array
	array_shift ($uploaded_pins); // remove .. from array
	// print_r ($uploaded_pins);

	// GET SLUGS ASSOCATED ARRAY
	$result 		= $mysqli->query("SELECT id, slug FROM `$tbl_name` WHERE web=1 AND pin=0");
	while ($row = $result->fetch_array()) {

		$id			=	$row['id'];
		$db_pin = $row['slug'] . '.jpg';

		$status = array_search($db_pin, $uploaded_pins);

		// IF NOT FOUND IN FILES DELETE FROM PIN DB
		if (empty($status)){
			// echo 'Not Found: ' . $status .' '. $db_pin;
			// echo "UPDATE `$tbl_name` SET web=7 WHERE id=$id";  echo "<br />";
			$mysqli->query("UPDATE `$tbl_name` SET web=7 WHERE id=$id");
		}

	}//while
	///////////////////////////////////////
?>
<div class="content-wrapper">
	<div class="page-header page-header-default">
		<div class="page-header-content">
			<div class="page-title text-center">
				<h4> Sync Pins </h4>
				<!-- <small>Analyze your online Presense and Generate more leads</small><br /> -->
			</div>
		</div>
	</div>
	<div class="content">

		<?php include('admin_elements/breadcrumb.php');?>

		<div class="row">
			<div class="col-sm-12 col-md-12">
				<div class="panel panel-body bg-success-400 has-bg-image">
					<div class="media no-margin">
						<div class="media-body">
							<?php
								$result = $mysqli->query("SELECT id FROM `$tbl_name` WHERE web=1 AND pin=0");
							?>
								<h3 class="no-margin"><?php echo $result->num_rows;?> db_remaining</h3>

							<?php
								$fi = new FilesystemIterator('../pins', FilesystemIterator::SKIP_DOTS);
							?>
								<h3 class="no-margin"><?php echo iterator_count($fi);?> pins_dirctory</h3>

							<?php
								$result = $mysqli->query("SELECT id FROM `$tbl_name` WHERE web=1 AND pin=1");
								echo ' [Posted <strong>'.$result->num_rows.'</strong>] ';
							?><br />

							<a data-pin-do="embedUser" data-pin-board-width="1200" data-pin-scale-height="440" data-pin-scale-width="80" href="https://www.pinterest.com/haiuaedotcom/pins/"></a>
						</div>

					</div>
				</div>
			</div>

		</div>

  </div>

		<?php include('admin_elements/copyright.php');?>
	</div>
</div>
<?php include('admin_elements/admin_footer.php');?>
