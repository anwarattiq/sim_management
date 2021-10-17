<?php
include('admin_elements/admin_header.php');
$module = 'statistics';
$module_caption = 'Statistics';
$tbl_name = $tbl_prefix . $module;
$error_message = '';
$success_message = '';
#########################################

if ($_SESSION[$project_pre]['type'] != 'superadmin') {
 header('Location: sims_request.php');   
}


$result = $mysqli->query("SELECT * FROM mt_companies ");
$total_number_of_companies = $result->num_rows;
///////////////////////////////////////////////////

$result = $mysqli->query("SELECT * FROM mt_employees ");
$total_number_of_employee = $result->num_rows;
/////////////////////////////////////////////////////

$result = $mysqli->query("SELECT * FROM mt_employees_sims ");
$total_number_of_assign_sims = $result->num_rows;
/////////////////////////////////////////////////////

$result = $mysqli->query("SELECT * FROM mt_sims ");
$total_number_of_sims = $result->num_rows;
/////////////////////////////////////////////////////

$result = $mysqli->query("SELECT * FROM mt_sims_request ");
$total_number_of_request = $result->num_rows;

////////////////////////////////////////////////
$result = $mysqli->query("SELECT * FROM mt_sims_request where status='Rejected' ");
$total_number_of_reject = $result->num_rows;

/////////////////

////////////////////////////////////////////////
$result = $mysqli->query("SELECT * FROM mt_sims_request where status='Accepted' ");
$total_number_of_approved = $result->num_rows;

###################################

/**
 **********
    @@@ GRAPHS
 **********
 **/
// $date_range				= date("d-m-Y", strtotime("-1 months", time())) .' - '.date('d-m-Y', time());
// if (isset($_REQUEST['date_range']) && !empty($_REQUEST['date_range']))
// 	$date_range = $mysqli->real_escape_string(stripslashes($_REQUEST['date_range']));
// 	$dates 								= explode('-', $date_range);

// 	$start_date 					= $dates[0].'-'.$dates[1].'-'.$dates[2];
// 	$end_date 						= $dates[3].'-'.$dates[4].'-'.$dates[5];
// 	$end_date 						= date('d-m-Y', strtotime($end_date . ' +1 day')); // plus 1 days

// 	$graph_start_date 		= new DateTime( $start_date );
//   	$graph_end_date 			= new DateTime( $end_date );

// include ('admin_elements/fusioncharts_js.php');
///////////////////////////////////////////////////////////////////////////////
?>
<style>
    .no-margin a{
        color:#fff !important;
    }
</style>

<div class="content-wrapper">
	<?php include('admin_elements/breadcrumb.php'); ?>

	<!-- Content area -->
	<div class="content">

		<div class="content-wrapper">

			<!-- Page header -->
			<div class="page-header">
				<div class="page-header-content">
					<div class="page-title">
						<h4><i class="icon-circle-right2 position-left"></i> <span class="text-semibold">Fustika Group </span> - Mobiles Tracker</h4>
						<a class="heading-elements-toggle"><i class="icon-more"></i></a>
					</div>

					<div class="heading-elements">
						<div class="heading-btn-group">
							<a href="#" class="btn btn-link btn-float has-text"><i class="icon-bars-alt text-primary"></i><span>Reports</span></a>
							<!-- <a href="#" class="btn btn-link btn-float has-text"><i class="icon-calculator text-primary"></i> <span>Invoices</span></a> -->
							<!-- <a href="#" class="btn btn-link btn-float has-text"><i class="icon-calendar5 text-primary"></i> <span>Schedule</span></a> -->
						</div>
					</div>
				</div>

				<div class="breadcrumb-line breadcrumb-line-component"><a class="breadcrumb-elements-toggle"><i class="icon-menu-open"></i></a>
					<ul class="breadcrumb">
						<li><a href="index.php"><i class="icon-home2 position-left"></i> Dashboard</a></li>
						<!-- <li><a href="general_widgets_stats.html">General pages</a></li> -->
						<!-- <li class="active">Stats widgets</li> -->
					</ul>

					<!-- <ul class="breadcrumb-elements">
						<li><a href="#"><i class="icon-comment-discussion position-left"></i> Support</a></li>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<i class="icon-gear position-left"></i>
								Settings
								<span class="caret"></span>
							</a>

							<ul class="dropdown-menu dropdown-menu-right">
								<li><a href="#"><i class="icon-user-lock"></i> Account security</a></li>
								<li><a href="#"><i class="icon-statistics"></i> Analytics</a></li>
								<li><a href="#"><i class="icon-accessibility"></i> Accessibility</a></li>
								<li class="divider"></li>
								<li><a href="#"><i class="icon-gear"></i> All settings</a></li>
							</ul>
						</li>
					</ul> -->
				</div>
			</div>
			<!-- /page header -->


			<!-- Content area -->
			<div class="content">

				<!-- Simple statistics -->
				<h6 class="content-group text-semibold">
					General Stats
					<!-- <small class="display-block">Boxes with icons</small> -->
				</h6>

				<div class="row">
					<div class="col-sm-6 col-md-3">
						<div class="panel panel-body panel-body-accent">
							<div class="media no-margin">
								<div class="media-left media-middle">
									<i class="icon-pointer icon-3x text-success-400"></i>
								</div>

								<div class="media-body text-right">
									<h3 class="no-margin text-semibold">
				<a href="listing_sims.php" style="color:#000!important;"><?php echo $total_number_of_sims;?></a></h3>
									<span class="text-uppercase text-size-mini text-muted">Total  SIMs</span>
								</div>
							</div>
						</div>
					</div>

					<div class="col-sm-6 col-md-3">
						<div class="panel panel-body">
							<div class="media no-margin">
								<div class="media-left media-middle">
									<i class="icon-enter6 icon-3x text-indigo-400"></i>
								</div>

								<div class="media-body text-right">
									<h3 class="no-margin text-semibold"><a href="listing_sims.php" style="color:#000!important;"><?php echo $total_number_of_assign_sims; ?></a></h3>
									<span class="text-uppercase text-size-mini text-muted">Total Assign Sims</span>
								</div>
							</div>
						</div>
					</div>

					<div class="col-sm-6 col-md-3">
						<div class="panel panel-body">
							<div class="media no-margin">
								<div class="media-body">
									<h3 class="no-margin text-semibold"><a href="listing_sims.php" style="color:#000!important;" ><?php echo $total_number_of_sims-$total_number_of_assign_sims;?></a></h3>
									<span class="text-uppercase text-size-mini text-muted">Unassigned Sims</span>
								</div>

								<div class="media-right media-middle">
									<i class="icon-bubbles4 icon-3x text-blue-400"></i>
								</div>
							</div>
						</div>
					</div>

					<div class="col-sm-6 col-md-3">
						<div class="panel panel-body">
							<div class="media no-margin">
								<div class="media-body">
									<h3 class="no-margin text-semibold"><a href="listing_employees.php" style="color:#000!important;"><?php echo $total_number_of_employee;?></a></h3>
									<span class="text-uppercase text-size-mini text-muted">Total Employees</span>
								</div>

								<div class="media-right media-middle">
									<i class="icon-bag icon-3x text-danger-400"></i>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-sm-6 col-md-3">
						<div class="panel panel-body bg-blue-400 has-bg-image">
							<div class="media no-margin">
								<div class="media-body">
									<h3 class="no-margin"><a href="listing_sims_request.php"><?php echo $total_number_of_request;?></a></h3>
									<span class="text-uppercase text-size-mini">Total Request</span>
								</div>

								<div class="media-right media-middle">
									<i class="icon-bubbles4 icon-3x opacity-75"></i>
								</div>
							</div>
						</div>
					</div>

					<div class="col-sm-6 col-md-3">
						<div class="panel panel-body bg-danger-400 has-bg-image">
							<div class="media no-margin">
								<div class="media-body">
									<h3 class="no-margin"><a href="listing_sims_request.php"><?php echo $total_number_of_reject;?></a></h3>
									<span class="text-uppercase text-size-mini">Total Reject</span>
								</div>

								<div class="media-right media-middle">
									<i class="icon-bag icon-3x opacity-75"></i>
								</div>
							</div>
						</div>
					</div>

					<div class="col-sm-6 col-md-3">
						<div class="panel panel-body bg-success-400 has-bg-image">
							<div class="media no-margin">
								<div class="media-left media-middle">
									<i class="icon-pointer icon-3x opacity-75"></i>
								</div>

								<div class="media-body text-right">
									<h3 class="no-margin"><a href="listing_sims_request.php"><?php echo $total_number_of_approved;?></a></h3>
									<span class="text-uppercase text-size-mini">Total Approved</span>
								</div>
							</div>
						</div>
					</div>

					<div class="col-sm-6 col-md-3">
						<div class="panel panel-body bg-indigo-400 has-bg-image">
							<div class="media no-margin">
								<div class="media-left media-middle">
									<i class="icon-enter6 icon-3x opacity-75"></i>
								</div>

								<div class="media-body text-right">
									<h3 class="no-margin">
						<a href="listing_companies.php"><?php echo $total_number_of_companies;?><a/></h3>
									<span class="text-uppercase text-size-mini">Total Companies</span>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- /simple statistics -->


				<!-- Statistics with progress bar -->
				<!-- <h6 class="content-group text-semibold">
					Progress stats
					<small class="display-block">Boxes with progress bars</small>
				</h6>

				<div class="row">
					<div class="col-sm-6 col-md-3">
						<div class="panel panel-body">
							<div class="media no-margin-top content-group">
								<div class="media-body">
									<h6 class="no-margin text-semibold">Server maintenance</h6>
									<span class="text-muted">Until 1st of June</span>
								</div>

								<div class="media-right media-middle">
									<i class="icon-cog3 icon-2x text-indigo-400 opacity-75"></i>
								</div>
							</div>

							<div class="progress progress-micro mb-10">
								<div class="progress-bar bg-indigo-400" style="width: 67%">
									<span class="sr-only">67% Complete</span>
								</div>
							</div>
							<span class="pull-right">67%</span>
							Re-indexing
						</div>
					</div>

					<div class="col-sm-6 col-md-3">
						<div class="panel panel-body">
							<div class="media no-margin-top content-group">
								<div class="media-body">
									<h6 class="no-margin text-semibold">Services status</h6>
									<span class="text-muted">April, 19th</span>
								</div>

								<div class="media-right media-middle">
									<i class="icon-pulse2 icon-2x text-danger-400 opacity-75"></i>
								</div>
							</div>

							<div class="progress progress-micro mb-10">
								<div class="progress-bar bg-danger-400" style="width: 80%">
									<span class="sr-only">80% Complete</span>
								</div>
							</div>
							<span class="pull-right">80%</span>
							Partly operational
						</div>
					</div>

					<div class="col-sm-6 col-md-3">
						<div class="panel panel-body">
							<div class="media no-margin-top content-group">
								<div class="media-left media-middle">
									<i class="icon-cog3 icon-2x text-blue-400 opacity-75"></i>
								</div>

								<div class="media-body">
									<h6 class="no-margin text-semibold">Server maintenance</h6>
									<span class="text-muted">Until 1st of June</span>
								</div>
							</div>

							<div class="progress progress-micro mb-10">
								<div class="progress-bar bg-blue" style="width: 67%">
									<span class="sr-only">67% Complete</span>
								</div>
							</div>
							<span class="pull-right">67%</span>
							Re-indexing
						</div>
					</div>

					<div class="col-sm-6 col-md-3">
						<div class="panel panel-body">
							<div class="media no-margin-top content-group">
								<div class="media-left media-middle">
									<i class="icon-pulse2 icon-2x text-success-400 opacity-75"></i>
								</div>

								<div class="media-body">
									<h6 class="no-margin text-semibold">Services status</h6>
									<span class="text-muted">April, 19th</span>
								</div>
							</div>

							<div class="progress progress-micro mb-10">
								<div class="progress-bar bg-success-400" style="width: 80%">
									<span class="sr-only">80% Complete</span>
								</div>
							</div>
							<span class="pull-right">80%</span>
							Partly operational
						</div>
					</div>
				</div> -->

				<!-- <div class="row">
					<div class="col-sm-6 col-md-3">
						<div class="panel panel-body bg-blue-400 has-bg-image">
							<div class="media no-margin-top content-group">
								<div class="media-body">
									<h6 class="no-margin text-semibold">Server maintenance</h6>
									<span class="text-muted">Until 1st of June</span>
								</div>

								<div class="media-right media-middle">
									<i class="icon-cog3 icon-2x"></i>
								</div>
							</div>

							<div class="progress progress-micro bg-blue mb-10">
								<div class="progress-bar bg-white" style="width: 67%">
									<span class="sr-only">67% Complete</span>
								</div>
							</div>
							<span class="pull-right">67%</span>
							Re-indexing
						</div>
					</div>

					<div class="col-sm-6 col-md-3">
						<div class="panel panel-body bg-danger-400 has-bg-image">
							<div class="media no-margin-top content-group">
								<div class="media-body">
									<h6 class="no-margin text-semibold">Services status</h6>
									<span class="text-muted">April, 19th</span>
								</div>

								<div class="media-right media-middle">
									<i class="icon-pulse2 icon-2x"></i>
								</div>
							</div>

							<div class="progress progress-micro mb-10 bg-danger">
								<div class="progress-bar bg-white" style="width: 80%">
									<span class="sr-only">80% Complete</span>
								</div>
							</div>
							<span class="pull-right">80%</span>
							Partly operational
						</div>
					</div>

					<div class="col-sm-6 col-md-3">
						<div class="panel panel-body bg-success-400 has-bg-image">
							<div class="media no-margin-top content-group">
								<div class="media-left media-middle">
									<i class="icon-cog3 icon-2x"></i>
								</div>

								<div class="media-body">
									<h6 class="no-margin text-semibold">Server maintenance</h6>
									<span class="text-muted">Until 1st of June</span>
								</div>
							</div>

							<div class="progress progress-micro mb-10 bg-success">
								<div class="progress-bar bg-white" style="width: 67%">
									<span class="sr-only">67% Complete</span>
								</div>
							</div>
							<span class="pull-right">67%</span>
							Re-indexing
						</div>
					</div>

					<div class="col-sm-6 col-md-3">
						<div class="panel panel-body bg-indigo-400 has-bg-image">
							<div class="media no-margin-top content-group">
								<div class="media-left media-middle">
									<i class="icon-pulse2 icon-2x"></i>
								</div>

								<div class="media-body">
									<h6 class="no-margin text-semibold">Services status</h6>
									<span class="text-muted">April, 19th</span>
								</div>
							</div>

							<div class="progress progress-micro mb-10 bg-indigo">
								<div class="progress-bar bg-white" style="width: 80%">
									<span class="sr-only">80% Complete</span>
								</div>
							</div>
							<span class="pull-right">80%</span>
							Partly operational
						</div>
					</div>
				</div> -->
				<!-- /statistics with progress bar -->


	<script type="text/javascript" src="js/loader.js"></script>
   
     <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['Task', 'Number Of sims'],
          ['Assigned',     <?php echo $total_number_of_assign_sims;?>],
          ['Unassigned',      <?php echo $total_number_of_sims-$total_number_of_assign_sims;?>]
       
         
        ]);

        var options = {
          title: 'Sims Record'
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
      }
      
    </script>
      <?php
    $str='';
    $result_sims = $mysqli->query("SELECT sum(amount)as amount,sum(`mt_invoice`.qty) as qty,company_name FROM `mt_invoice` inner join `mt_sims_request` on `mt_sims_request`.id=mt_invoice.sims_request_id inner join mt_companies on mt_companies.id=mt_sims_request.company group by mt_sims_request.company ");
    
    foreach($result_sims as $array){
        
        $str .='["'.$array['company_name'].'", '.$array['amount'].', '.$array['qty'].'],';
    }
   
    ?>
  <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart', 'bar']});
      google.charts.setOnLoadCallback(drawStuff);

      function drawStuff() {

        var button = document.getElementById('change-chart');
        var chartDiv = document.getElementById('chart_div');

        var data = google.visualization.arrayToDataTable([
          ['Company', 'Amount', 'QTY'],
         <?php echo $str;?>
        ]);

        var materialOptions = {
          width: 450,
          chart: {
          
          },
          series: {
            0: { axis: 'Amount' }, // Bind series 0 to an axis named 'distance'.
            1: { axis: 'QTY' } // Bind series 1 to an axis named 'brightness'.
          },
          axes: {
            y: {
              distance: {label: 'parsecs'}, // Left y-axis.
              brightness: {side: 'right', label: 'apparent magnitude'} // Right y-axis.
            }
          }
        };

        var classicOptions = {
          width: 450,
          series: {
            0: {targetAxisIndex: 0},
            1: {targetAxisIndex: 1}
          },
          title: 'Nearby galaxies - distance on the left, brightness on the right',
          vAxes: {
            // Adds titles to each axis.
            0: {title: 'parsecs'},
            1: {title: 'apparent magnitude'}
          }
        };

        function drawMaterialChart() {
          var materialChart = new google.charts.Bar(chartDiv);
          materialChart.draw(data, google.charts.Bar.convertOptions(materialOptions));
          button.innerText = 'Change to Classic';
          button.onclick = drawClassicChart;
        }

        function drawClassicChart() {
          var classicChart = new google.visualization.ColumnChart(chartDiv);
          classicChart.draw(data, classicOptions);
          button.innerText = 'Change to Material';
          button.onclick = drawMaterialChart;
        }

        drawMaterialChart();
    };
    </script>
     <br />
   
   
 
 
   
     <!-- Dashboard content -->
        <div class="row">
            <div class="col-lg-12">

              
                    <div class="row">

                        <div class="col-md-6">
                            <div class="panel panel-flat">
                                <div class="panel-heading">

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-9">
                                           <b>Expense By Companies</b>
    	<table id="grid4-<?php echo $module; ?>" class="display responsive no-wrap table table-striped table-bordered table-hover">
					<thead>
						<tr>
							<th >#</th>
							<th >Company</th>
						
							<th>Quantity</th>
							<th>Amount</th>
						
							
							<!-- <th>Created</th> -->
							<!-- <th></th> -->
						</tr>
						
					</thead>
					<tbody>
					<?php 
					$i=1;
					foreach($result_sims as $array){
						
						echo '<tr><td>'.$i.'</td><td>'.$array['company_name'].'</td><td>'.$array['qty'].'</td><td>'.$array['amount'].'</td></tr>';
						
				$i++;	}?>
    </table>    
<br />
   <div id="piechart" ></div><br />
    <?php
    $str='';
    $result_sims = $mysqli->query("SELECT company_name,count(mt_employees.id)as number_of_empolyee from mt_companies left join mt_employees on mt_employees.company=mt_companies.id group by company_name");
    
    foreach($result_sims as $array){
        
        $str .='["'.$array['company_name'].'", '.$array['number_of_empolyee'].', "green"],';
    }
   
    ?>

 <script type="text/javascript">
    google.charts.load("current", {packages:['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
      var data = google.visualization.arrayToDataTable([
        ["City", "Employee", { role: "style" } ],
      <?php echo $str;?>
      ]);

      var view = new google.visualization.DataView(data);
      view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" },
                       2]);

      var options = {
        title: "Number Of Employee in Company",
        width: 450,
        height: 400,
        bar: {groupWidth: "95%"},
        legend: { position: "none" },
      };
      var chart = new google.visualization.LineChart(document.getElementById("columnchart1_values"));
      chart.draw(view, options);
  }
  </script>
<div id="columnchart1_values" ></div>
   
   
   
   
   
                                </div>

                            </div>
                        </div> </div>

                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="panel panel-flat">
                                <div class="panel-heading">

                                    <div class="row">
                                        <div class="col-md-9">
                                            <div class="col-md-12">
                                                <h5>-</h5>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="panel-body">
<div id="chart_div" ></div><br />


  <?php
    $str='';
    $result_sims = $mysqli->query("SELECT city_name,count(mt_employees.id)as number_of_empolyee  from mt_cities left join mt_employees on mt_employees.city=mt_cities.id group by city_name");
    
    foreach($result_sims as $array){
        
        $str .='["'.$array['city_name'].'", '.$array['number_of_empolyee'].', "red"],';
    }
   
    ?>

 <script type="text/javascript">
    google.charts.load("current", {packages:['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
      var data = google.visualization.arrayToDataTable([
        ["City", "Employee", { role: "style" } ],
      <?php echo $str;?>
      ]);

      var view = new google.visualization.DataView(data);
      view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" },
                       2]);

      var options = {
        title: "Number Of Employee via Cities Detail",
        width: 450,
        height: 400,
        bar: {groupWidth: "95%"},
        legend: { position: "none" },
      };
      var chart = new google.visualization.ColumnChart(document.getElementById("columnchart_values"));
      chart.draw(view, options);
  }
  </script>
<div id="columnchart_values" ></div>








                                </div>

                            </div>
                        </div>


                    </div>
                </form>



            </div>


        </div>
    </div>
    <!-- /d
   
   
  





			</div>
			<!-- /content area -->

		</div>

		<?php include('admin_elements/copyright.php'); ?>

	</div>
	<!-- /content area -->

</div>

<?php include('admin_elements/admin_footer.php'); ?>