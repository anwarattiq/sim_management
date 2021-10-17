<script type="text/javascript">
  FusionCharts.ready(function(){

      // **************************//
      // **** VERIFIED DAILY ***** //
      // **************************//
      var fusioncharts_verifieddaily = new FusionCharts({
        type: 'column2d',
        renderAt: 'chart-verifieddaily',
        width: '100%',
        height: '250',
        dataFormat: 'json',
        dataSource: {
            // Chart Configuration
            "chart": {
                "caption": "Verified Companies",
                "subCaption": "<?php echo date('d M, Y', strtotime($start_date));?> to <?php echo date('d M, Y', strtotime($end_date));?>",
                // "xAxisName": "Country",
                // "yAxisName": "Reserves (MMbbl)",
                // "numberSuffix": "K",
                "theme": "fusion",
            },

            // Chart Data
            "data": [
            <?php
              $interval = DateInterval::createFromDateString('1 day');
              $period 	= new DatePeriod($graph_start_date, $interval, $graph_end_date);

              foreach ($period as $dt) {
                  $day = $dt->format("d-m-Y");
                  $total_verified = $mysqli->query("SELECT id FROM `".$GLOBALS['TBL']['PREFIX']."companies` WHERE verified=1 AND created LIKE '".processDateDtoY($day)."%' ");
            ?>
                { "label": "<?php echo $day;?>", "value": "<?php echo $total_verified->num_rows;?>"},
            <?php }//foreach ?>
            ]
        }
      });
      fusioncharts_verifieddaily.render();

      // **************************//
      // **** Searches DAILY ***** //
      // **************************//
      var fusioncharts_verifieddaily = new FusionCharts({
        type: 'column2d',
        renderAt: 'chart-searchesdaily',
        width: '100%',
        height: '250',
        dataFormat: 'json',
        dataSource: {
            // Chart Configuration
            "chart": {
                "caption": "Daily Search",
                "subCaption": "<?php echo date('d M, Y', strtotime($start_date));?> to <?php echo date('d M, Y', strtotime($end_date));?>",
                // "xAxisName": "Country",
                // "yAxisName": "Reserves (MMbbl)",
                // "numberSuffix": "K",
                "theme": "fusion",
            },

            // Chart Data
            "data": [
            <?php
              $interval = DateInterval::createFromDateString('1 day');
              $period 	= new DatePeriod($graph_start_date, $interval, $graph_end_date);

              foreach ($period as $dt) {
                  $day = $dt->format("d-m-Y");
                  $total_verified = $mysqli->query("SELECT id FROM `".$GLOBALS['TBL']['PREFIX']."searches` WHERE search_keyword!='' AND created LIKE '".processDateDtoY($day)."%' ");
            ?>
                { "label": "<?php echo $day;?>", "value": "<?php echo $total_verified->num_rows;?>"},
            <?php }//foreach ?>
            ]
        }
      });
      fusioncharts_verifieddaily.render();

      // **************************//
      // *****VERIFIED ANNUALLY ******//
      // **************************//
      var fusioncharts_verifiedannually = new FusionCharts({
        type: 'column2d',
        renderAt: 'chart-verifiedannually',
        width: '100%',
        height: '250',
        dataFormat: 'json',
        dataSource: {
            // Chart Configuration
            "chart": {
                "caption": "Annual Verified Companies",
                // "xAxisName": "Country",
                // "yAxisName": "Reserves (MMbbl)",
                // "numberSuffix": "K",
                "theme": "fusion",
            },

            // Chart Data
            "data": [
            <?php
              $current_year = date('Y');
              for ($year=2017; $year <= $current_year; $year++) {
                  $total_verifiedannually = $mysqli->query("SELECT id FROM `".$GLOBALS['TBL']['PREFIX']."companies` WHERE verified=1 AND publish=1 AND created LIKE '".$year."%' ");
            ?>
                { "label": "<?php echo $year;?>", "value": "<?php echo $total_verifiedannually->num_rows;?>"},
            <?php }//foreach ?>
            ]
        }
      });
      fusioncharts_verifiedannually.render();

      <?php if (!isRemote()){?>

      // **************************//
      // ****** COMPANY SOURCES *********//
      // **************************//
      var fusioncharts_companysources2 = new FusionCharts({
        type: 'msColumn3D',
        renderAt: 'chart-companysources2',
        width: '100%',
        height: '800',
        dataFormat: 'json',
        dataSource: {
        "chart": {
            "theme": "fusion",
            "caption": "Company Sources / views",
            "subCaption": "Last 30 days",
            // "yAxisname": "Sales (In USD)",
            // "numberPrefix": "$"
        },
        "categories": [
            {
                "category": [
                  <?php
                    $result_companysources_graph = $mysqli->query("SELECT sitemap_slug FROM `".$GLOBALS['TBL']['PREFIX']."company_sources` WHERE total_companies>0 ORDER BY total_companies");
                    while ($row_companysources_graph = $result_companysources_graph->fetch_array()) {
                  ?>
                  { "label": "<?php echo $row_companysources_graph['sitemap_slug'];?>"},
                  <?php }//while?>
                ]
            }
        ],
        "dataset": [
            {
                "seriesname": "Source",
                "data": [
                  <?php
                    $result_companysources_graph = $mysqli->query("SELECT total_companies FROM `".$GLOBALS['TBL']['PREFIX']."company_sources` WHERE total_companies>0 ORDER BY total_companies");
                    while ($row_companysources_graph = $result_companysources_graph->fetch_array()) {
                  ?>
                  { "value": "<?php echo $row_companysources_graph['total_companies'];?>"},
                  <?php }//while?>
                ]
            },
            {
                "seriesname": "Visits",
                "data": [
                  <?php
                    $result_companysources_graph = $mysqli->query("SELECT id FROM `".$GLOBALS['TBL']['PREFIX']."company_sources` WHERE total_companies>0 ORDER BY total_companies");
                    while ($row_companysources_graph = $result_companysources_graph->fetch_array()) {
                      $g_source_id = $row_companysources_graph['id'];

                      // $g_rs_companies   = $mysqli->query("SELECT id FROM `".$GLOBALS['TBL']['PREFIX']."companies` WHERE source=$g_source_id AND last_visit LIKE '2019-09-%'");

                      $s__ = date('Y-m-d H:i:s', strtotime('today - 30 days'));
                      $e__ = date('Y-m-d H:i:s', time());
                      $g_rs_companies   = $mysqli->query("SELECT id FROM `".$GLOBALS['TBL']['PREFIX']."companies` WHERE source=$g_source_id AND last_visit BETWEEN '".$s__."' AND '".$e__."'");
                      $g_row_companies  = $g_rs_companies->num_rows;

                  ?>
                  { "value": "<?php echo $g_row_companies;?>"},
                  <?php }//while?>
                ]
            }
        ],
        // "trendlines": [
        //     {
        //         "line": [
        //             {
        //                 "startvalue": "15000",
        //                 "color": "#5D62B5",
        //                 "valueOnRight": "1",
        //                 "displayvalue": "Avg. for{br}Food"
        //             },
        //             {
        //                 "startvalue": "22000",
        //                 "color": "#29C3BE",
        //                 "valueOnRight": "1",
        //                 "displayvalue": "Avg. for{br}Non-food"
        //             }
        //         ]
        //     }
        // ]
    }
      });
      fusioncharts_companysources2.render();

      <?php }?>

  });
</script>
