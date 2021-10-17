<?php
	include('admin_elements/admin_header.php');
	#########################################
?>

<div class="content-wrapper">

	<?php include('admin_elements/breadcrumb.php');?>

	<!-- Content area -->
	<div class="content">

		<!-- Dashboard content -->
		<div class="row">
			<div class="col-lg-6">

				<div class="row">
					<div class="col-md-12">
						<?php
							$result = $mysqli->query("SELECT id FROM `".$GLOBALS['TBL']['PREFIX']."companies_verified` WHERE web=1 AND pin=0");
							echo ' <strong>'.$result->num_rows.'</strong> db_remaining &nbsp;&nbsp;&nbsp;&nbsp;';

							echo ' <a href="sync_pins.php" data-popup="tooltip" title="" data-placement="bottom" data-original-title="Sync Pins"><i class="icon-git-compare position-left"></i>Sync</a> &nbsp;&nbsp;&nbsp;&nbsp;';

							$fi = new FilesystemIterator('../pins', FilesystemIterator::SKIP_DOTS);
							echo ' <strong>'.iterator_count($fi).'</strong> pins_dirctory &nbsp;&nbsp;&nbsp;&nbsp;';

							$result = $mysqli->query("SELECT id FROM `".$GLOBALS['TBL']['PREFIX']."companies_verified` WHERE web=1 AND pin=1");
							echo ' [Posted <strong>'.$result->num_rows.'</strong>] ';
						?><br />
						<a data-pin-do="embedBoard" data-pin-board-width="500" data-pin-scale-height="240" data-pin-scale-width="80" href="https://www.pinterest.com/imranonline_net/internet-is-awesome/"></a>
					</div>
				</div>

			</div>


			<div class="col-lg-6">

				<div class="row">
					<div class="col-md-12">
						<a class="twitter-timeline" data-width="100%" data-height="800" data-theme="light" data-link-color="#2B7BB9" href="https://twitter.com/ImranOnline_net">ImranOnline_net</a> <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
					</div>
				</div>
			</div>


			</div>
		</div>
		<!-- /dashboard content -->

		<?php include('admin_elements/copyright.php');?>

	</div>
	<!-- /content area -->

</div>

<?php include('admin_elements/admin_footer.php');?>
