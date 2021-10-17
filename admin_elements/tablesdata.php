<?php
$grid_page =
	array(
		'listing_pages.php',
		'listing_articles.php',
		'listing_sims_request',
		'listing_article_categories.php',
		'listing_projects.php',
		'listing_project_categories.php',
		'listing_tags.php',
		'listing_companies.php',
		'listing_cities.php',
		'listing_telecoms.php',
		'listing_sims.php',
		'listing_sim_packages.php',
		'listing_employees.php',
		'listing_services.php',
		'listing_service_tabs.php',
		'listing_admins.php',
		'listing_admin_logs.php',
		'listing_admin_blocks.php',
		'listing_admin_activities.php'
	);

if (isset($_REQUEST['type']) && !empty($_REQUEST['type'])) 	$type = $_REQUEST['type'];
else $type = '';
if (isset($_REQUEST['manager']) && !empty($_REQUEST['manager'])) 	$manager = $_REQUEST['manager'];
else $manager = '';
if (isset($_REQUEST['list_id']) && !empty($_REQUEST['list_id'])) 	$list_id = $_REQUEST['list_id'];
else $list_id = '';
if (isset($_REQUEST['camp_id']) && !empty($_REQUEST['camp_id']))	$camp_id = $_REQUEST['camp_id'];
else $camp_id = '';
if (isset($_REQUEST['tbl']) && !empty($_REQUEST['tbl']))	$tbl = $_REQUEST['tbl'];
else $tbl = '';

if (in_array($current_page, $grid_page)) {
?>

	<!-- <link rel="stylesheet" type="text/css" href="css/jquery.dataTables.css"> -->
	<!-- <script type="text/javascript" src="js/jquery.dataTables.js"></script> -->

	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
	<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>


	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css">
	<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>

	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css">
	<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>


	<script type="text/javascript">
		$(document).ready(function() {
			<?php
			switch ($current_page) {

					///////// LISTING /////////
				case 'listing_pages.php':
					$module = 'pages';
			?>
					var dataTable = $('#grid-<?php echo $module; ?>').DataTable({
						"responsive": true,
						"iDisplayLength": 50,
						"language": {
							"searchPlaceholder": "page",
							"sLengthMenu": "Show _MENU_" //remove entries text from the -> Show dropdown entries
						},
						"fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
							if (aData[dataTable.column(':contains("Manage")').index()].match('=publish_')) {
								$(nRow).css('color', 'silver');
							}
						},
						// "dom": '<"top"i>rt<"bottom"flp><"clear">',
						// "dom": '<"top"iflp<"clear">>rt<"bottom"iflp<"clear">>',
						"searchHighlight": true,
						"processing": true,
						"serverSide": true,
						"ajax": {
							url: "datatables.php", // json datasource
							type: "post", // method  , by default get
							data: {
								ajax_action: "listing_<?php echo $module; ?>",
								module: "<?php echo $module; ?>",
								action: "<?php echo $action; ?>",
								type: "<?php echo $type; ?>"
							},
							error: function() { // error handling
								$(".grid-error").html("");
								$("#grid-<?php echo $module; ?>").append('<tbody class="grid-error"><tr><th colspan="3">No Results Found.</th></tr></tbody>');
								$("#grid-<?php echo $module; ?>_processing").css("display", "none");
							}
						}
					});
					var ordering_column = dataTable.column(':contains("php_page")').index();
					dataTable.order([ordering_column, 'desc']).draw();


				<?php
					break;
	///////// LISTING /////////
				case 'listing_sims_request.php':
					$module = 'pages';
			?>
					var dataTable = $('#grid-<?php echo $module; ?>').DataTable({
						"responsive": true,
						"iDisplayLength": 50,
						"language": {
							"searchPlaceholder": "page",
							"sLengthMenu": "Show _MENU_" //remove entries text from the -> Show dropdown entries
						},
						"fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
							if (aData[dataTable.column(':contains("Manage")').index()].match('=publish_')) {
								$(nRow).css('color', 'silver');
							}
						},
						// "dom": '<"top"i>rt<"bottom"flp><"clear">',
						// "dom": '<"top"iflp<"clear">>rt<"bottom"iflp<"clear">>',
						"searchHighlight": true,
						"processing": true,
						"serverSide": true,
						"ajax": {
							url: "datatables.php", // json datasource
							type: "post", // method  , by default get
							data: {
								ajax_action: "listing_<?php echo $module; ?>",
								module: "<?php echo $module; ?>",
								action: "<?php echo $action; ?>",
								type: "<?php echo $type; ?>"
							},
							error: function() { // error handling
								$(".grid-error").html("");
								$("#grid-<?php echo $module; ?>").append('<tbody class="grid-error"><tr><th colspan="3">No Results Found.</th></tr></tbody>');
								$("#grid-<?php echo $module; ?>_processing").css("display", "none");
							}
						}
					});
					var ordering_column = dataTable.column(':contains("php_page")').index();
					dataTable.order([ordering_column, 'desc']).draw();


				<?php
					break;
					///////// LISTING /////////
				case 'listing_articles.php':
					$module = 'articles';
				?>
					var dataTable = $('#grid-<?php echo $module; ?>').DataTable({
						"responsive": true,
						"iDisplayLength": 10,
						"language": {
							"searchPlaceholder": "Article Title",
							"sLengthMenu": "Show _MENU_" //remove entries text from the -> Show dropdown entries
						},
						"fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
							if (aData[dataTable.column(':contains("Manage")').index()].match('=publish_')) {
								$(nRow).css('color', 'silver');
							}
						},
						// "dom": '<"top"i>rt<"bottom"flp><"clear">',
						// "dom": '<"top"iflp<"clear">>rt<"bottom"iflp<"clear">>',
						"searchHighlight": true,
						"processing": true,
						"serverSide": true,
						"ajax": {
							url: "datatables.php", // json datasource
							type: "post", // method  , by default get
							data: {
								ajax_action: "listing_<?php echo $module; ?>",
								module: "<?php echo $module; ?>",
								action: "<?php echo $action; ?>",
								type: "<?php echo $type; ?>"
							},
							error: function() { // error handling
								$(".grid-error").html("");
								$("#grid-<?php echo $module; ?>").append('<tbody class="grid-error"><tr><th colspan="3">No Results Found.</th></tr></tbody>');
								$("#grid-<?php echo $module; ?>_processing").css("display", "none");
							}
						}
					});
					// var ordering_column = dataTable.column('0').index();
					var ordering_column = dataTable.column(':contains("Created")').index();
					dataTable.order([ordering_column, 'desc']).draw();

				<?php
					break;

					///////// LISTING /////////
				case 'listing_article_categories.php':
					$module = 'article_categories';
				?>
					var dataTable = $('#grid-<?php echo $module; ?>').DataTable({
						"responsive": true,
						"iDisplayLength": 10,
						"language": {
							"searchPlaceholder": "Category",
							"sLengthMenu": "Show _MENU_" //remove entries text from the -> Show dropdown entries
						},
						"fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
							if (aData[dataTable.column(':contains("Manage")').index()].match('=publish_')) {
								$(nRow).css('color', 'silver');
							}
						},
						// "dom": '<"top"i>rt<"bottom"flp><"clear">',
						// "dom": '<"top"iflp<"clear">>rt<"bottom"iflp<"clear">>',
						"searchHighlight": true,
						"processing": true,
						"serverSide": true,
						"ajax": {
							url: "datatables.php", // json datasource
							type: "post", // method  , by default get
							data: {
								ajax_action: "listing_<?php echo $module; ?>",
								module: "<?php echo $module; ?>",
								action: "<?php echo $action; ?>",
								type: "<?php echo $type; ?>"
							},
							error: function() { // error handling
								$(".grid-error").html("");
								$("#grid-<?php echo $module; ?>").append('<tbody class="grid-error"><tr><th colspan="3">No Results Found.</th></tr></tbody>');
								$("#grid-<?php echo $module; ?>_processing").css("display", "none");
							}
						}
					});
					var ordering_column = dataTable.column(':contains("Category")').index();
					dataTable.order([ordering_column, 'asc']).draw();



				<?php
					break;

					///////// LISTING /////////
				case 'listing_projects.php':
					$module = 'projects';
				?>
					var dataTable = $('#grid-<?php echo $module; ?>').DataTable({
						"responsive": true,
						"iDisplayLength": 10,
						"language": {
							"searchPlaceholder": "Project Title",
							"sLengthMenu": "Show _MENU_" //remove entries text from the -> Show dropdown entries
						},
						"fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
							if (aData[dataTable.column(':contains("Manage")').index()].match('=publish_')) {
								$(nRow).css('color', 'silver');
							}
						},
						// "dom": '<"top"i>rt<"bottom"flp><"clear">',
						// "dom": '<"top"iflp<"clear">>rt<"bottom"iflp<"clear">>',
						"searchHighlight": true,
						"processing": true,
						"serverSide": true,
						"ajax": {
							url: "datatables.php", // json datasource
							type: "post", // method  , by default get
							data: {
								ajax_action: "listing_<?php echo $module; ?>",
								module: "<?php echo $module; ?>",
								action: "<?php echo $action; ?>",
								type: "<?php echo $type; ?>"
							},
							error: function() { // error handling
								$(".grid-error").html("");
								$("#grid-<?php echo $module; ?>").append('<tbody class="grid-error"><tr><th colspan="3">No Results Found.</th></tr></tbody>');
								$("#grid-<?php echo $module; ?>_processing").css("display", "none");
							}
						}
					});
					var ordering_column = dataTable.column('0').index();
					dataTable.order([ordering_column, 'desc']).draw();

				<?php
					break;

					///////// LISTING /////////
				case 'listing_project_categories.php':
					$module = 'project_categories';
				?>
					var dataTable = $('#grid-<?php echo $module; ?>').DataTable({
						"responsive": true,
						"iDisplayLength": 10,
						"language": {
							"searchPlaceholder": "Category",
							"sLengthMenu": "Show _MENU_" //remove entries text from the -> Show dropdown entries
						},
						"fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
							if (aData[dataTable.column(':contains("Manage")').index()].match('=publish_')) {
								$(nRow).css('color', 'silver');
							}
						},
						// "dom": '<"top"i>rt<"bottom"flp><"clear">',
						// "dom": '<"top"iflp<"clear">>rt<"bottom"iflp<"clear">>',
						"searchHighlight": true,
						"processing": true,
						"serverSide": true,
						"ajax": {
							url: "datatables.php", // json datasource
							type: "post", // method  , by default get
							data: {
								ajax_action: "listing_<?php echo $module; ?>",
								module: "<?php echo $module; ?>",
								action: "<?php echo $action; ?>",
								type: "<?php echo $type; ?>"
							},
							error: function() { // error handling
								$(".grid-error").html("");
								$("#grid-<?php echo $module; ?>").append('<tbody class="grid-error"><tr><th colspan="3">No Results Found.</th></tr></tbody>');
								$("#grid-<?php echo $module; ?>_processing").css("display", "none");
							}
						}
					});
					var ordering_column = dataTable.column(':contains("Category")').index();
					dataTable.order([ordering_column, 'asc']).draw();

				<?php
					break;

					///////// LISTING /////////
				case 'listing_tags.php':
					$module = 'tags';
				?>
					var dataTable = $('#grid-<?php echo $module; ?>').DataTable({
						"responsive": true,
						"iDisplayLength": 100,
						"language": {
							"searchPlaceholder": "Tag",
							"sLengthMenu": "Show _MENU_" //remove entries text from the -> Show dropdown entries
						},
						"fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
							if (aData[dataTable.column(':contains("Manage")').index()].match('=publish_')) {
								$(nRow).css('color', 'silver');
							}
						},
						// "dom": '<"top"i>rt<"bottom"flp><"clear">',
						// "dom": '<"top"iflp<"clear">>rt<"bottom"iflp<"clear">>',
						"searchHighlight": true,
						"processing": true,
						"serverSide": true,
						"ajax": {
							url: "datatables.php", // json datasource
							type: "post", // method  , by default get
							data: {
								ajax_action: "listing_<?php echo $module; ?>",
								module: "<?php echo $module; ?>",
								action: "<?php echo $action; ?>",
								type: "<?php echo $type; ?>"
							},
							error: function() { // error handling
								$(".grid-error").html("");
								$("#grid-<?php echo $module; ?>").append('<tbody class="grid-error"><tr><th colspan="3">No Results Found.</th></tr></tbody>');
								$("#grid-<?php echo $module; ?>_processing").css("display", "none");
							}
						}
					});
					var ordering_column = dataTable.column(':contains("Manage")').index();
					dataTable.order([ordering_column, 'desc']).draw();


				<?php
					break;

					///////// LISTING /////////
				case 'listing_companies.php':
					$module = 'companies';
				?>
					var dataTable = $('#grid-<?php echo $module; ?>').DataTable({
						"responsive": true,
						"iDisplayLength": 100,
						"language": {
							"searchPlaceholder": "company name",
							"sLengthMenu": "Show _MENU_" //remove entries text from the -> Show dropdown entries
						},
						"fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
							if (aData[dataTable.column(':contains("Manage")').index()].match('=publish_')) {
								$(nRow).css('color', 'silver');
							}
						},
						// "dom": '<"top"i>rt<"bottom"flp><"clear">',
						// "dom": '<"top"iflp<"clear">>rt<"bottom"iflp<"clear">>',
						"searchHighlight": true,
						"processing": true,
						"serverSide": true,
						"ajax": {
							url: "datatables.php", // json datasource
							type: "post", // method  , by default get
							data: {
								ajax_action: "listing_<?php echo $module; ?>",
								module: "<?php echo $module; ?>",
								action: "<?php echo $action; ?>",
								type: "<?php echo $type; ?>"
							},
							error: function() { // error handling
								$(".grid-error").html("");
								$("#grid-<?php echo $module; ?>").append('<tbody class="grid-error"><tr><th colspan="3">No Results Found.</th></tr></tbody>');
								$("#grid-<?php echo $module; ?>_processing").css("display", "none");
							}
						}
					});
					var ordering_column = dataTable.column(':contains("Company Name")').index();
					dataTable.order([ordering_column, 'asc']).draw();


				<?php
					break;

					///////// LISTING /////////
				case 'listing_cities.php':
					$module = 'cities';
				?>
					var dataTable = $('#grid-<?php echo $module; ?>').DataTable({
						"responsive": true,
						"iDisplayLength": 100,
						"language": {
							"searchPlaceholder": "city name",
							"sLengthMenu": "Show _MENU_" //remove entries text from the -> Show dropdown entries
						},
						"fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
							if (aData[dataTable.column(':contains("Manage")').index()].match('=publish_')) {
								$(nRow).css('color', 'silver');
							}
						},
						// "dom": '<"top"i>rt<"bottom"flp><"clear">',
						// "dom": '<"top"iflp<"clear">>rt<"bottom"iflp<"clear">>',
						"searchHighlight": true,
						"processing": true,
						"serverSide": true,
						"ajax": {
							url: "datatables.php", // json datasource
							type: "post", // method  , by default get
							data: {
								ajax_action: "listing_<?php echo $module; ?>",
								module: "<?php echo $module; ?>",
								action: "<?php echo $action; ?>",
								type: "<?php echo $type; ?>"
							},
							error: function() { // error handling
								$(".grid-error").html("");
								$("#grid-<?php echo $module; ?>").append('<tbody class="grid-error"><tr><th colspan="3">No Results Found.</th></tr></tbody>');
								$("#grid-<?php echo $module; ?>_processing").css("display", "none");
							}
						}
					});
					var ordering_column = dataTable.column(':contains("City Name")').index();
					dataTable.order([ordering_column, 'asc']).draw();


				<?php
					break;

					///////// LISTING /////////
				case 'listing_telecoms.php':
					$module = 'telecoms';
				?>
					var dataTable = $('#grid-<?php echo $module; ?>').DataTable({
						"responsive": true,
						"iDisplayLength": 100,
						"language": {
							"searchPlaceholder": "company name",
							"sLengthMenu": "Show _MENU_" //remove entries text from the -> Show dropdown entries
						},
						"fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
							if (aData[dataTable.column(':contains("Manage")').index()].match('=publish_')) {
								$(nRow).css('color', 'silver');
							}
						},
						// "dom": '<"top"i>rt<"bottom"flp><"clear">',
						// "dom": '<"top"iflp<"clear">>rt<"bottom"iflp<"clear">>',
						"searchHighlight": true,
						"processing": true,
						"serverSide": true,
						"ajax": {
							url: "datatables.php", // json datasource
							type: "post", // method  , by default get
							data: {
								ajax_action: "listing_<?php echo $module; ?>",
								module: "<?php echo $module; ?>",
								action: "<?php echo $action; ?>",
								type: "<?php echo $type; ?>"
							},
							error: function() { // error handling
								$(".grid-error").html("");
								$("#grid-<?php echo $module; ?>").append('<tbody class="grid-error"><tr><th colspan="3">No Results Found.</th></tr></tbody>');
								$("#grid-<?php echo $module; ?>_processing").css("display", "none");
							}
						}
					});
					var ordering_column = dataTable.column(':contains("Company Name")').index();
					dataTable.order([ordering_column, 'asc']).draw();

				<?php
					break;

					///////// LISTING /////////
				case 'listing_sims.php':
					$module = 'sims';
				?>
					var dataTable = $('#grid-<?php echo $module; ?>').DataTable({
						"responsive": true,
						"iDisplayLength": 50,
						"language": {
							"searchPlaceholder": "sim number",
							"sLengthMenu": "Show _MENU_" //remove entries text from the -> Show dropdown entries
						},
						"fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
							if (aData[dataTable.column(':contains("Manage")').index()].match('=publish_')) {
								$(nRow).css('color', 'silver');
							}
						},
						// "dom": '<"top"i>rt<"bottom"flp><"clear">',
						// "dom": '<"top"iflp<"clear">>rt<"bottom"iflp<"clear">>',
						"searchHighlight": true,
						"processing": true,
						"serverSide": true,
						"ajax": {
							url: "datatables.php", // json datasource
							type: "post", // method  , by default get
							data: {
								ajax_action: "listing_<?php echo $module; ?>",
								module: "<?php echo $module; ?>",
								action: "<?php echo $action; ?>",
								type: "<?php echo $type; ?>"
							},
							error: function() { // error handling
								$(".grid-error").html("");
								$("#grid-<?php echo $module; ?>").append('<tbody class="grid-error"><tr><th colspan="3">No Results Found.</th></tr></tbody>');
								$("#grid-<?php echo $module; ?>_processing").css("display", "none");
							}
						}
					});
					var ordering_column = dataTable.column(':contains("Manage")').index();
					dataTable.order([ordering_column, 'asc']).draw();

				<?php
					break;

					///////// LISTING /////////
				case 'listing_sim_packages.php':
					$module = 'sim_packages';
				?>
					var dataTable = $('#grid-<?php echo $module; ?>').DataTable({
						"responsive": true,
						"iDisplayLength": 25,
						"language": {
							"searchPlaceholder": "package name",
							"sLengthMenu": "Show _MENU_" //remove entries text from the -> Show dropdown entries
						},
						"fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
							if (aData[dataTable.column(':contains("Manage")').index()].match('=publish_')) {
								$(nRow).css('color', 'silver');
							}
						},
						// "dom": '<"top"i>rt<"bottom"flp><"clear">',
						// "dom": '<"top"iflp<"clear">>rt<"bottom"iflp<"clear">>',
						"searchHighlight": true,
						"processing": true,
						"serverSide": true,
						"ajax": {
							url: "datatables.php", // json datasource
							type: "post", // method  , by default get
							data: {
								ajax_action: "listing_<?php echo $module; ?>",
								module: "<?php echo $module; ?>",
								action: "<?php echo $action; ?>",
								type: "<?php echo $type; ?>"
							},
							error: function() { // error handling
								$(".grid-error").html("");
								$("#grid-<?php echo $module; ?>").append('<tbody class="grid-error"><tr><th colspan="3">No Results Found.</th></tr></tbody>');
								$("#grid-<?php echo $module; ?>_processing").css("display", "none");
							}
						}
					});
					var ordering_column = dataTable.column(':contains("Manage")').index();
					dataTable.order([ordering_column, 'asc']).draw();




				<?php
					break;

					///////// LISTING /////////
				case 'listing_employees.php':
					$module = 'employees';
				?>
					var dataTable = $('#grid-<?php echo $module; ?>').DataTable({
						"responsive": true,
						"iDisplayLength": 50,
						"language": {
							"searchPlaceholder": "employee name",
							"sLengthMenu": "Show _MENU_" //remove entries text from the -> Show dropdown entries
						},
						"fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
							if (aData[dataTable.column(':contains("Manage")').index()].match('=publish_')) {
								$(nRow).css('color', 'silver');
							}
						},
						// "dom": '<"top"i>rt<"bottom"flp><"clear">',
						// "dom": '<"top"iflp<"clear">>rt<"bottom"iflp<"clear">>',
						"searchHighlight": true,
						"processing": true,
						"serverSide": true,
						"ajax": {
							url: "datatables.php", // json datasource
							type: "post", // method  , by default get
							data: {
								ajax_action: "listing_<?php echo $module; ?>",
								module: "<?php echo $module; ?>",
								action: "<?php echo $action; ?>",
								type: "<?php echo $type; ?>"
							},
							error: function() { // error handling
								$(".grid-error").html("");
								$("#grid-<?php echo $module; ?>").append('<tbody class="grid-error"><tr><th colspan="3">No Results Found.</th></tr></tbody>');
								$("#grid-<?php echo $module; ?>_processing").css("display", "none");
							}
						}
					});
					var ordering_column = dataTable.column(':contains("Manage")').index();
					dataTable.order([ordering_column, 'asc']).draw();


				<?php
					break;

					///////// LISTING /////////
				case 'listing_services.php':
					$module = 'services';
				?>
					var dataTable = $('#grid-<?php echo $module; ?>').DataTable({
						"responsive": true,
						"iDisplayLength": 25,
						"language": {
							"searchPlaceholder": "Service Name",
							"sLengthMenu": "Show _MENU_" //remove entries text from the -> Show dropdown entries
						},
						"fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
							if (aData[dataTable.column(':contains("Manage")').index()].match('=publish_')) {
								$(nRow).css('color', 'silver');
							}
						},
						// "dom": '<"top"i>rt<"bottom"flp><"clear">',
						// "dom": '<"top"iflp<"clear">>rt<"bottom"iflp<"clear">>',
						"searchHighlight": true,
						"processing": true,
						"serverSide": true,
						"ajax": {
							url: "datatables.php", // json datasource
							type: "post", // method  , by default get
							data: {
								ajax_action: "listing_<?php echo $module; ?>",
								module: "<?php echo $module; ?>",
								action: "<?php echo $action; ?>",
								type: "<?php echo $type; ?>"
							},
							error: function() { // error handling
								$(".grid-error").html("");
								$("#grid-<?php echo $module; ?>").append('<tbody class="grid-error"><tr><th colspan="3">No Results Found.</th></tr></tbody>');
								$("#grid-<?php echo $module; ?>_processing").css("display", "none");
							}
						}
					});
					var ordering_column = dataTable.column(':contains("Manage")').index();
					dataTable.order([ordering_column, 'desc']).draw();


				<?php
					break;

					///////// LISTING /////////
				case 'listing_service_tabs.php':
					$module = 'service_tabs';
				?>
					var dataTable = $('#grid-<?php echo $module; ?>').DataTable({
						"responsive": true,
						"iDisplayLength": 10,
						"language": {
							"searchPlaceholder": "Tab name",
							"sLengthMenu": "Show _MENU_" //remove entries text from the -> Show dropdown entries
						},
						"fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
							if (aData[dataTable.column(':contains("Manage")').index()].match('=publish_')) {
								$(nRow).css('color', 'silver');
							}
						},
						// "dom": '<"top"i>rt<"bottom"flp><"clear">',
						// "dom": '<"top"iflp<"clear">>rt<"bottom"iflp<"clear">>',
						"searchHighlight": true,
						"processing": true,
						"serverSide": true,
						"ajax": {
							url: "datatables.php", // json datasource
							type: "post", // method  , by default get
							data: {
								ajax_action: "listing_<?php echo $module; ?>",
								module: "<?php echo $module; ?>",
								action: "<?php echo $action; ?>",
								type: "<?php echo $type; ?>"
							},
							error: function() { // error handling
								$(".grid-error").html("");
								$("#grid-<?php echo $module; ?>").append('<tbody class="grid-error"><tr><th colspan="3">No Results Found.</th></tr></tbody>');
								$("#grid-<?php echo $module; ?>_processing").css("display", "none");
							}
						}
					});
					var ordering_column = dataTable.column(':contains("Manage")').index();
					dataTable.order([ordering_column, 'desc']).draw();



				<?php
					break;
					///////// LISTING /////////
				case 'listing_inquiries.php':
					$module = 'inquiries';
				?>
					var dataTable = $('#grid-<?php echo $module; ?>').DataTable({
						"iDisplayLength": 5,
						"language": {
							"sLengthMenu": "Show _MENU_" //remove entries text from the -> Show dropdown entries
						},
						"searching": false,
						"processing": true,
						"serverSide": true,
						"columnDefs": [{
							"targets": 0,
							"orderable": false,
							"searchable": false
						}],
						"ajax": {
							url: "datatables.php", // json datasource
							type: "post", // method  , by default get
							data: {
								ajax_action: "listing_<?php echo $module; ?>",
								module: "<?php echo $module; ?>",
								action: "<?php echo $action; ?>"
							},
							error: function() { // error handling
								$(".grid-error").html("");
								$("#grid-<?php echo $module; ?>").append('<tbody class="grid-error"><tr><th colspan="3">No Results Found.</th></tr></tbody>');
								$("#grid-<?php echo $module; ?>_processing").css("display", "none");
							}
						},
					});

					// BULK DELETE INQUIRIES CODE
					$("#bulkDelete").on('click', function() { // bulk checked
						var status = this.checked;
						$(".deleteRow").each(function() {
							$(this).prop("checked", status);
						});
					});
					$('#deleteTriger').on("click", function(event) { // triggering delete one by one
						if ($('.deleteRow:checked').length > 0) { // at-least one checkbox checked
							var ids = [];
							$('.deleteRow').each(function() {
								if ($(this).is(':checked')) {
									ids.push($(this).val());
								}
							});
							var ids_string = ids.toString(); // array to string conversion
							$.ajax({
								method: "POST",
								url: "<?php echo $admin_base_url; ?>/bulk_delete_inquiries.php",
								data: {
									data_ids: ids_string
								},
								success: function(result) {
									dataTable.draw(); // redrawing datatable
								},
								// async:false
							});
						}
					});
					var ordering_column = dataTable.column(':contains("Received")').index();
					dataTable.order([ordering_column, 'desc']).draw();

				<?php
					break;

					///////// LISTING /////////
				case 'listing_email_lists.php':
					$module = 'email_lists';
				?>
					var dataTable = $('#grid-<?php echo $module; ?>').DataTable({
						"iDisplayLength": 25,
						"language": {
							"searchPlaceholder": "list name",
							"sLengthMenu": "Show _MENU_" //remove entries text from the -> Show dropdown entries
						},
						// "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
						// 	if (aData[dataTable.column( ':contains("Manage")' ).index()].match('=publish_')) {
						// 		$(nRow).css('color', 'silver');
						// 	}
						// },
						"dom": '<"top"iflp<"clear">>rt<"bottom"iflp<"clear">>',
						"searchHighlight": true,
						"processing": true,
						"serverSide": true,
						"ajax": {
							url: "datatables.php", // json datasource
							type: "post", // method  , by default get
							data: {
								ajax_action: "listing_<?php echo $module; ?>",
								module: "<?php echo $module; ?>",
								action: "<?php echo $action; ?>"
							},
							error: function() { // error handling
								$(".grid-error").html("");
								$("#grid-<?php echo $module; ?>").append('<tbody class="grid-error"><tr><th colspan="3">No Results Found.</th></tr></tbody>');
								$("#grid-<?php echo $module; ?>_processing").css("display", "none");
							}
						}
					});
					// $(".dataTables_info").hide();
					var ordering_column = dataTable.column(':contains("List Name")').index();
					dataTable.order([ordering_column, 'desc']).draw();

				<?php
					break;

					///////// LISTING /////////
				case 'listing_subscribers.php':
					$module = 'subscribers';
				?>
					var dataTable = $('#grid-<?php echo $module; ?>').DataTable({
						"iDisplayLength": 25,
						"language": {
							"searchPlaceholder": "email",
							"sLengthMenu": "Show _MENU_" //remove entries text from the -> Show dropdown entries
						},
						"fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
							if (aData[dataTable.column(':contains("Manage")').index()].match('=publish_')) {
								$(nRow).css('color', 'silver');
							}
						},
						"dom": '<"top"iflp<"clear">>rt<"bottom"iflp<"clear">>',
						"searchHighlight": true,
						"processing": true,
						"serverSide": true,
						"ajax": {
							url: "datatables.php", // json datasource
							type: "post", // method  , by default get
							data: {
								ajax_action: "listing_<?php echo $module; ?>",
								module: "<?php echo $module; ?>",
								action: "<?php echo $action; ?>",
								list_id: "<?php echo $list_id; ?>"
							},
							error: function() { // error handling
								$(".grid-error").html("");
								$("#grid-<?php echo $module; ?>").append('<tbody class="grid-error"><tr><th colspan="3">No Results Found.</th></tr></tbody>');
								$("#grid-<?php echo $module; ?>_processing").css("display", "none");
							}
						}
					});
					// var ordering_column = dataTable.column( ':contains("Manage")' ).index();
					// dataTable.order( [ ordering_column, 'desc' ] ).draw();

				<?php
					break;

					///////// LISTING /////////
				case 'listing_email_campaigns.php':
					$module = 'email_campaigns';
				?>
					var dataTable = $('#grid-<?php echo $module; ?>').DataTable({
						"iDisplayLength": 10,
						"language": {
							"searchPlaceholder": "Company Name",
							"sLengthMenu": "Show _MENU_" //remove entries text from the -> Show dropdown entries
						},
						"fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
							if (aData[dataTable.column(':contains("Manage")').index()].match('=publish_')) {
								$(nRow).css('color', 'silver');
							}
						},
						"searchHighlight": true,
						"processing": true,
						"serverSide": true,
						"ajax": {
							url: "datatables.php", // json datasource
							type: "post", // method  , by default get
							data: {
								ajax_action: "listing_<?php echo $module; ?>",
								module: "<?php echo $module; ?>",
								action: "<?php echo $action; ?>"
							},
							error: function() { // error handling
								$(".grid-error").html("");
								$("#grid-<?php echo $module; ?>").append('<tbody class="grid-error"><tr><th colspan="3">No Results Found.</th></tr></tbody>');
								$("#grid-<?php echo $module; ?>_processing").css("display", "none");
							}
						}
					});
					var ordering_column = dataTable.column(':contains("Manage")').index();
					dataTable.order([ordering_column, 'desc']).draw();



				<?php
				case 'listing_email_templates.php':
					$module = 'email_templates';
				?>
					var dataTable = $('#grid-<?php echo $module; ?>').DataTable({
						"responsive": true,
						"iDisplayLength": 10,
						"language": {
							"searchPlaceholder": "template name",
							"sLengthMenu": "Show _MENU_" //remove entries text from the -> Show dropdown entries
						},
						// "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
						// 	if (aData[dataTable.column(':contains("Manage")').index()].match('=publish_')) {
						// 		$(nRow).css('color', 'silver');
						// 	}
						// },
						// "dom": '<"top"i>rt<"bottom"flp><"clear">',
						// "dom": '<"top"iflp<"clear">>rt<"bottom"iflp<"clear">>',
						"searchHighlight": true,
						"processing": true,
						"serverSide": true,
						"ajax": {
							url: "datatables.php", // json datasource
							type: "post", // method  , by default get
							data: {
								ajax_action: "listing_<?php echo $module; ?>",
								module: "<?php echo $module; ?>",
								action: "<?php echo $action; ?>",
								type: "<?php echo $type; ?>"
							},
							error: function() { // error handling
								$(".grid-error").html("");
								$("#grid-<?php echo $module; ?>").append('<tbody class="grid-error"><tr><th colspan="3">No Results Found.</th></tr></tbody>');
								$("#grid-<?php echo $module; ?>_processing").css("display", "none");
							}
						}
					});
					var ordering_column = dataTable.column(':contains("Manage")').index();
					dataTable.order([ordering_column, 'desc']).draw();



				<?php
				case 'listing_email_targets.php':
					$module = 'email_targets';
				?>
					var dataTable = $('#grid-<?php echo $module; ?>').DataTable({
						"responsive": true,
						"iDisplayLength": 25,
						"language": {
							"searchPlaceholder": "target name",
							"sLengthMenu": "Show _MENU_" //remove entries text from the -> Show dropdown entries
						},
						"fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
							if (aData[dataTable.column(':contains("Manage")').index()].match('=publish_')) {
								$(nRow).css('color', 'silver');
							}
						},
						// "dom": '<"top"i>rt<"bottom"flp><"clear">',
						// "dom": '<"top"iflp<"clear">>rt<"bottom"iflp<"clear">>',
						"searchHighlight": true,
						"processing": true,
						"serverSide": true,
						"ajax": {
							url: "datatables.php", // json datasource
							type: "post", // method  , by default get
							data: {
								ajax_action: "listing_<?php echo $module; ?>",
								module: "<?php echo $module; ?>",
								action: "<?php echo $action; ?>",
								type: "<?php echo $type; ?>"
							},
							error: function() { // error handling
								$(".grid-error").html("");
								$("#grid-<?php echo $module; ?>").append('<tbody class="grid-error"><tr><th colspan="3">No Results Found.</th></tr></tbody>');
								$("#grid-<?php echo $module; ?>_processing").css("display", "none");
							}
						}
					});
					var ordering_column = dataTable.column(':contains("Manage")').index();
					dataTable.order([ordering_column, 'desc']).draw();


				<?php
				case 'listing_email_providers.php':
					$module = 'email_providers';
				?>
					var dataTable = $('#grid-<?php echo $module; ?>').DataTable({
						"responsive": true,
						"iDisplayLength": 25,
						"language": {
							"searchPlaceholder": "provider name",
							"sLengthMenu": "Show _MENU_" //remove entries text from the -> Show dropdown entries
						},
						"fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
							if (aData[dataTable.column(':contains("Manage")').index()].match('=publish_')) {
								$(nRow).css('color', 'silver');
							}
						},
						// "dom": '<"top"i>rt<"bottom"flp><"clear">',
						// "dom": '<"top"iflp<"clear">>rt<"bottom"iflp<"clear">>',
						"searchHighlight": true,
						"processing": true,
						"serverSide": true,
						"ajax": {
							url: "datatables.php", // json datasource
							type: "post", // method  , by default get
							data: {
								ajax_action: "listing_<?php echo $module; ?>",
								module: "<?php echo $module; ?>",
								action: "<?php echo $action; ?>",
								type: "<?php echo $type; ?>"
							},
							error: function() { // error handling
								$(".grid-error").html("");
								$("#grid-<?php echo $module; ?>").append('<tbody class="grid-error"><tr><th colspan="3">No Results Found.</th></tr></tbody>');
								$("#grid-<?php echo $module; ?>_processing").css("display", "none");
							}
						}
					});
					var ordering_column = dataTable.column(':contains("Manage")').index();
					dataTable.order([ordering_column, 'desc']).draw();


				<?php
					break;

					///////// LISTING /////////
				case 'listing_email_reports.php':
					$module = 'email_reports';
				?>
					var dataTable = $('#grid-<?php echo $module; ?>').DataTable({
						"iDisplayLength": 25,
						"language": {
							"searchPlaceholder": "Email",
							"sLengthMenu": "Show _MENU_" //remove entries text from the -> Show dropdown entries
						},
						// "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
						// 	if (aData[dataTable.column( ':contains("Manage")' ).index()].match('=publish_')) {
						// 		$(nRow).css('color', 'silver');
						// 	}
						// },
						"searchHighlight": true,
						"processing": true,
						"serverSide": true,
						"ajax": {
							url: "datatables.php", // json datasource
							type: "post", // method  , by default get
							data: {
								ajax_action: "listing_<?php echo $module; ?>",
								module: "<?php echo $module; ?>",
								action: "<?php echo $action; ?>",
								camp_id: "<?php echo $camp_id; ?>"
							},
							error: function() { // error handling
								$(".grid-error").html("");
								$("#grid-<?php echo $module; ?>").append('<tbody class="grid-error"><tr><th colspan="3">No Results Found.</th></tr></tbody>');
								$("#grid-<?php echo $module; ?>_processing").css("display", "none");
							}
						}
					});
					// var ordering_column = dataTable.column( ':contains("Manage")' ).index();
					// dataTable.order( [ ordering_column, 'desc' ] ).draw();


				<?php
					break;

					///////// LISTING /////////
				case 'listing_admins.php':
					$module = 'admins';
				?>
					var dataTable = $('#grid-<?php echo $module; ?>').DataTable({
						"iDisplayLength": 10,
						"language": {
							"searchPlaceholder": "username",
							"sLengthMenu": "Show _MENU_" //remove entries text from the -> Show dropdown entries
						},
						"fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
							if (aData[dataTable.column(':contains("Manage")').index()].match('=active_')) {
								$(nRow).css('color', 'silver');
							}
						},
						// "dom": '<"top"i>rt<"bottom"flp><"clear">',
						// "dom": '<"top"iflp<"clear">>rt<"bottom"iflp<"clear">>',
						"searchHighlight": true,
						"processing": true,
						"serverSide": true,
						"ajax": {
							url: "datatables.php", // json datasource
							type: "post", // method  , by default get
							data: {
								ajax_action: "listing_<?php echo $module; ?>",
								module: "<?php echo $module; ?>",
								action: "<?php echo $action; ?>"
							},
							error: function() { // error handling
								$(".grid-error").html("");
								$("#grid-<?php echo $module; ?>").append('<tbody class="grid-error"><tr><th colspan="3">No Results Found.</th></tr></tbody>');
								$("#grid-<?php echo $module; ?>_processing").css("display", "none");
							}
						}
					});

				<?php
					break;

					///////// LISTING /////////
				case 'listing_admin_logs.php':
					$module = 'admin_logs';
				?>
					var dataTable = $('#grid-<?php echo $module; ?>').DataTable({
						"iDisplayLength": 10,
						"language": {
							"searchPlaceholder": "ip address",
							"sLengthMenu": "Show _MENU_" //remove entries text from the -> Show dropdown entries
						},
						"searchHighlight": true,
						"processing": true,
						"serverSide": true,
						"ajax": {
							url: "datatables.php", // json datasource
							type: "post", // method  , by default get
							data: {
								ajax_action: "listing_<?php echo $module; ?>",
								module: "<?php echo $module; ?>",
								action: "<?php echo $action; ?>"
							},
							error: function() { // error handling
								$(".grid-error").html("");
								$("#grid-<?php echo $module; ?>").append('<tbody class="grid-error"><tr><th colspan="3">No Results Found.</th></tr></tbody>');
								$("#grid-<?php echo $module; ?>_processing").css("display", "none");
							}
						}
					});
					var ordering_column = dataTable.column(':contains("Admin Login Date Time")').index();
					dataTable.order([ordering_column, 'desc']).draw();

				<?php
					break;

					///////// LISTING /////////
				case 'listing_admin_blocks.php':
					$module = 'admin_blocks';
				?>
					var dataTable = $('#grid-<?php echo $module; ?>').DataTable({
						"iDisplayLength": 10,
						"language": {
							"searchPlaceholder": "ip address",
							"sLengthMenu": "Show _MENU_" //remove entries text from the -> Show dropdown entries
						},
						"searchHighlight": true,
						"processing": true,
						"serverSide": true,
						"ajax": {
							url: "datatables.php", // json datasource
							type: "post", // method  , by default get
							data: {
								ajax_action: "listing_<?php echo $module; ?>",
								module: "<?php echo $module; ?>",
								action: "<?php echo $action; ?>"
							},
							error: function() { // error handling
								$(".grid-error").html("");
								$("#grid-<?php echo $module; ?>").append('<tbody class="grid-error"><tr><th colspan="3">No Results Found.</th></tr></tbody>');
								$("#grid-<?php echo $module; ?>_processing").css("display", "none");
							}
						}
					});
					var ordering_column = dataTable.column(':contains("Blocked Login Time")').index();
					dataTable.order([ordering_column, 'desc']).draw();

				<?php
					break;

					///////// LISTING /////////
				case 'listing_admin_activities.php':
					$module = 'admin_activities';
				?>
					var dataTable = $('#grid-<?php echo $module; ?>').DataTable({
						"iDisplayLength": 25,
						"language": {
							"sLengthMenu": "Show _MENU_" //remove entries text from the -> Show dropdown entries
						},
						"searching": false,
						"processing": true,
						"serverSide": true,
						"ajax": {
							url: "datatables.php", // json datasource
							type: "post", // method  , by default get
							data: {
								ajax_action: "listing_<?php echo $module; ?>",
								module: "<?php echo $module; ?>"
							},
							error: function() { // error handling
								$(".grid-error").html("");
								$("#grid-<?php echo $module; ?>").append('<tbody class="grid-error"><tr><th colspan="3">No Results Found.</th></tr></tbody>');
								$("#grid-<?php echo $module; ?>_processing").css("display", "none");
							}
						}
					});

			<?php
					break;
			} //switch
			?>
		});
	</script>

<?php
} //endif
?>