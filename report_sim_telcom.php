<?php
include('admin_elements/admin_header.php');
$module = 'telecoms';
$module_caption = 'Telecom';
$tbl_name = $tbl_prefix . $module;
$photo_upload_path = '../uploads/' . $module . '/';
$error_message = '';
$success_message = '';
#########################################

/**
 ****************
	@@@ FEATURED @@@
 ****************
 **/

?>
<div class="content-wrapper">
	<div class="page-header page-header-default">
		<div class="page-header-content">
			<div class="page-title">
				<h4><i class="icon-circle-right2 position-left"></i> Listing <?php echo '<strong>' . ucwords('Report Telcom wise') . '</strong>'; ?> </h4>
				<a class="heading-elements-toggle"><i class="icon-more"></i></a>
			</div>
			<div class="heading-elements">
				<div class="heading-btn-group">
					
				</div>
			</div>
		</div>
	</div>
	<div class="content">

		<?php include('admin_elements/breadcrumb.php'); ?>

		<div class="row">
			<div class="col-md-12">
			    
			 		<div class="form-group">
										<label class="col-lg-3 control-label"><strong>Select Telcom</strong><span class="f_req">*</span></label>
									
										    
										    
											<select class="form-control"  name="telecom_name" id="telecom_name" onchange="function_city(this.value);">
											  <option value="asiacell" >Asia Cell - 0770</option>
                                            <option value="zain" >Zain - 0780</option>
											</select>
										</div>
									</div>
   
   
   
   
   
   
   
   <script type="text/javascript">
     function function_city(id){
         
       window.location.href ="https://shopinitools.com/sim/report_sim_telcom.php?cid="+id; 
     }
     
     </script>
   
   
   
   
   
   

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
      $telcom_name=$_REQUEST['id'];
      
    $str='';
    $result_sims = $mysqli->query("SELECT sum(amount)as amount,sum(`mt_invoice`.qty) as qty,company_name FROM `mt_invoice` inner join `mt_sims_request` on `mt_sims_request`.id=mt_invoice.sims_request_id inner join mt_companies on mt_companies.id=mt_sims_request.company
    where mt_sims_request.telecom_name='$telcom_name'
    group by mt_sims_request.company ");
    
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
		</div>
	


			</div>
			<!-- /content area -->

		</div>

		<?php include('admin_elements/copyright.php'); ?>

	</div>
	<!-- /content area -->

</div>

<?php include('admin_elements/admin_footer.php'); ?>