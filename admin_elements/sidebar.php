<div class="sidebar sidebar-main">
  <div class="sidebar-content">

    <!-- <div class="sidebar-user"> -->
      <!-- <div class="category-content"> -->
        <!-- <div class="media"> -->
          <!-- <a href="profile.php" class="media-left"><img src="<?php //echo $admin_base_url; ?>/uploads/admins/<?php //echo getMyAvatar($_SESSION[$project_pre]['admin_id']); ?>" class="img-circle img-sm" alt=""></a> -->
          <!-- <div class="media-body">
            <span class="media-heading text-semibold"><?php //echo $_SESSION[$project_pre]['full_name']; ?></span>
            <div class="text-size-mini text-muted">
              <?php //echo $_SESSION[$project_pre]['type']; ?>
            </div>
          </div> -->

        <!-- </div> -->
      <!-- </div> -->
    <!-- </div> -->

    <?php if (isset($_REQUEST['tbl']) && !empty($_REQUEST['tbl'])) $tbl = $mysqli->real_escape_string(stripslashes($_REQUEST['tbl']));
    else $tbl = ''; ?>

    <div class="sidebar-category sidebar-category-visible">
      <div class="category-content no-padding">
        <ul class="navigation navigation-main navigation-accordion">
<?php if($_SESSION['MT']['type']=='superadmin'){ ?>
          <li <?php if ($current_page == 'index.php') { ?>class="active" <?php } ?>><a href="index.php"> <span>Dashboard</span></a></li>

          <li <?php if ($current_page == 'listing_sims.php' || $current_page == 'sims.php') { ?>class="active" <?php } ?>>
            <a href="listing_sims.php"><i class="icon-mobile3"></i> <span>SIMs</span></a>
          </li><?php } ?>
<li> 
            <a href="#">
              <i class="icon-wrench3"></i> <span>SIMs Request Form</span>
            </a>
            <ul>
              <li <?php if ($current_page == 'sims_request.php' || $current_page == 'listing_sims_request.php') { ?>class="active" <?php } ?>>
                <a href="sims_request.php">
                  <i class="icon-mobile"></i>
                  <span>SIMs Request</span>
                </a>
              </li>
  <li <?php if ($current_page == 'sims_request.php' || $current_page == 'listing_sims_request.php') { ?>class="active" <?php } ?>>
                <a href="listing_sims_request.php">
                  <i class="icon-mobile"></i>
                  <span>SIMs Request Listing</span>
                </a>
              </li>
<li <?php if ($current_page == 'listing_invoice.php' || $current_page == 'listing_invoice.php') { ?>class="active" <?php } ?>>
                <a href="listing_invoice.php">
                  <i class="icon-mobile"></i>
                  <span>SIMs Request Invoice</span>
                </a>
              </li>        


</ul>
          </li>



          <li <?php if ($current_page == 'listing_employees.php' || $current_page == 'employees.php' || $current_page == 'assign_sim.php') { ?>class="active" <?php } ?>>
            <a href="listing_employees.php"><i class="icon-users2"></i> <span>Employees</span></a>
          </li>
<?php  if($_SESSION['MT']['type']=='superadmin'){ ?>
          <!-- <li>
            <a href="#">
              <i class="icon-users2"></i> <span>Employees</span>
            </a>
            <ul>
              <li <?php if ($current_page == 'listing_employees.php' || $current_page == 'employees.php') { ?>class="active" <?php } ?>>
                <a href="listing_employees.php"><i class="icon-users4"></i> <span>All</span></a>
              </li>

              <?php
              $result_c = $mysqli->query("SELECT * FROM `" . $GLOBALS['TBL']['PREFIX'] . "companies` WHERE publish=1 ORDER BY company_name");
              while ($rows_c = $result_c->fetch_array()) {
              ?>
                <li <?php if ($current_page == 'listing_sim_packages.php' || $current_page == 'sim_packages.php') { ?>class="active" <?php } ?>>
                  <a href="listing_sim_packages.php">
                    <span><?php echo $rows_c['company_name']; ?></span>
                  </a>
                </li>
              <?php
              } //while
              ?>
            </ul>
          </li> -->

          <li <?php if ($current_page == 'listing_companies.php' || $current_page == 'companies.php') { ?>class="active" <?php } ?>>
            <a href="listing_companies.php"><i class="icon-dribbble"></i> <span>Companies</span></a>
          </li>

          <li <?php if ($current_page == 'listing_cities.php' || $current_page == 'cities.php') { ?>class="active" <?php } ?>>
            <a href="listing_cities.php"><i class="icon-flag3"></i> <span>Cities</span></a>
          </li>


          <li>
            <a href="#">
              <i class="icon-wrench3"></i> <span>Settings</span>
            </a>
            <ul>
              <li <?php if ($current_page == 'listing_sim_packages.php' || $current_page == 'sim_packages.php') { ?>class="active" <?php } ?>>
                <a href="listing_sim_packages.php">
                  <i class="icon-mobile"></i>
                  <span>SIM Packages</span>
                </a>
              </li>
            
			<li <?php if ($current_page == 'admins.php' || $current_page == 'listing_admins.php') { ?>class="active" <?php } ?>>
                <a href="admins.php">
                  <i class="icon-mobile"></i>
                  <span>Manage Users</span>
                </a>
              </li>
			
			
			</ul>
			
			
			
          </li>

          <li>
            <a href="#">
              <i class="icon-graph"></i> <span>Reports</span>
            </a>
            <ul>
              <li <?php if ($current_page == '#' || $current_page == '#') { ?>class="active" <?php } ?>>
                <a href="report_sim_telcom.php">
                  <i class=" icon-arrow-right7"></i>
                  <span>By Telecom</span>
                </a>
              </li>
              <li <?php if ($current_page == '#' || $current_page == '#') { ?>class="active" <?php } ?>>
                <a href="report_sim_city.php">
                  <i class=" icon-arrow-right7"></i>
                  <span>By Cities</span>
                </a>
              </li>
            
			  <?php } ?>
            </ul>
          </li>

      </div>
    </div>

  </div>
</div>