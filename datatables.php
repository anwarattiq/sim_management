<?php

require('config/globals.php');
require('config/database.php');
require('config/images.php');


if (isset($_POST['ajax_action']) && !empty($_POST['ajax_action']))
  $ajax_action = $_POST['ajax_action'];
else
  $ajax_action = '';
if (isset($_POST['action']) && !empty($_POST['action']))
  $action = $_POST['action'];
else
  $action = '';
if (isset($_POST['module']) && !empty($_POST['module']))
  $module = $_POST['module'];
else
  $module = '';
if (isset($_POST['tbl']) && !empty($_POST['tbl']))
  $tbl = $_POST['tbl'];
else
  $tbl = '';
if (isset($_POST['type']) && !empty($_POST['type']))
  $type = $_POST['type'];
else
  $type = '';
if (isset($_POST['manager']) && !empty($_POST['manager']))
  $manager = $_POST['manager'];
else
  $manager = '';
if (isset($_POST['list_id']) && !empty($_POST['list_id']))
  $list_id = $_POST['list_id'];
else
  $list_id = '';
if (isset($_POST['camp_id']) && !empty($_POST['camp_id']))
  $camp_id = $_POST['camp_id'];
else
  $camp_id = '';
$tbl_name = $tbl_prefix . $module;
################################

/* ORDERING
  listing_pages
  listing_articles
  listing_article_categories
  listing_projects
  listing_project_categories
  listing_tags
  listing_companies
  listing_cities
  listing_telecoms
  listing_sims
  listing_sim_packages
  listing_employees
  email_targets // companies with applied targets
  listing_services
  listing_service_tabs
  listing_searches
  listing_admins
  listing_admin_logs
  listing_admin_activities
  listing_admin_blocks
 */

switch ($ajax_action) {

    ///////// LISTING /////////
    case 'listing_pages':

    $requestData = $_REQUEST;

    /** COUNT * */
    $sql_count = "SELECT count(id) FROM `$tbl_name` WHERE id > 0 ";
    if (!empty($requestData['search']['value']))
      $sql_count .= " AND menu_caption LIKE '" . $requestData['search']['value'] . "%' "; /*	  * *SEARCH QUERY ** */
    $query_count = $mysqli->query($sql_count);
    $row = $query_count->fetch_row();
    $totalData = $row[0];
    $totalFiltered = $totalData;

    $columns = array(
      0 => 'id',
      1 => 'php_page',
      2 => 'menu_caption',
      3 => 'php_page',
      4 => 'meta_title',
      5 => 'meta_description',
      6 => 'cache',
      7 => 'cache'
    );

    /*       * * QUERY ** */
    $data = array();
    $sql = "SELECT * FROM `$tbl_name` WHERE id >0 ";
    if (!empty($requestData['search']['value']))
      $sql .= " AND menu_caption LIKE '" . $requestData['search']['value'] . "%' "; /*	  * *SEARCH QUERY ** */
    $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "  ";
    $query = $mysqli->query($sql);

    $created = '';
    while ($row = $query->fetch_array()) {
      $nestedData = array();

      $id           = $row["id"];

      $slug             = $row["slug"];
      $menu_caption     = $row["menu_caption"];
      $php_page         = $row["php_page"];
      $cache            = $row["cache"];
      $main_menu        = $row["main_menu"];

      if ($php_page==1){
        $link_slug       = '<a href="' . $base_url . '/' . $slug . '" target="_blank">' . $base_url .'/'. $slug . '</a>';
        $php_page = '<span class="label bg-info text-semibold">Page</span>';
      } else {
        $link_slug       = '<a href="' . $base_url . '/page/' . $slug . '" target="_blank">' . $base_url .'/page/'. $slug . '</a>';
        $php_page = '<span class="label bg-info text-semibold">Content Page</span>';
      }

      $meta_title       = $row["meta_title"];
      $meta_description = $row["meta_description"];
      $publish          = $row["publish"];

      if ($publish == 1)
        $nestedData[] = drawPublishedEditDelete($module, $id, 0, 1, 0); //Publish, Edit, Delete
      else
        $nestedData[] = drawNotPublishedEditDelete($module, $id, 0, 1, 0); //Publish, Edit, Delete
      $nestedData[] = $php_page;
      $nestedData[] = '<strong>'.$menu_caption.'</strong><br />'.$link_slug;
      $nestedData[] = $meta_title;
      $nestedData[] = $meta_description;

      if ($cache == 1) {
        $cache = '<a href="listing_' . $module . '.php?action=nocache_' . $module . '&tbl=' . $tbl . '&id=' . $id . '"><span class="label label-success">Cache</span></a>';
      } else {
        $cache = '<a href="listing_' . $module . '.php?action=cache_' . $module . '&tbl=' . $tbl . '&id=' . $id . '">No</a>';
      }
      $nestedData[] = $cache;

      // $main_menu        = ($main_menu == 0) ? $main_menu = '<span class="label bg-info text-semibold">No</span>' : $main_menu = '<span class="label bg-info text-semibold">Main Menu</span>';
      if ($main_menu == 1) {
        $main_menu = '<a href="listing_' . $module . '.php?action=nomainmenu_' . $module . '&tbl=' . $tbl . '&id=' . $id . '"><span class="label bg-info text-semibold">Yes</span></a>';
      } else {
        $main_menu = '<a href="listing_' . $module . '.php?action=mainmenu_' . $module . '&tbl=' . $tbl . '&id=' . $id . '">No</a>';
      }
      $nestedData[] = $main_menu;


      $data[] = $nestedData;
    }
    break;

    ///////// LISTING /////////
    case 'listing_articles':

    $requestData = $_REQUEST;
    /** COUNT * */
    $sql_count = "SELECT count(id) FROM `$tbl_name` WHERE id > 0 ";
    if ($type == 'haitrends') $sql_count .= ' AND created_utc > 0 ';
    else $sql_count .= ' AND created_utc = 0 ';
    if (!empty($requestData['search']['value']))
      $sql_count .= " AND article_title LIKE '%" . $requestData['search']['value'] . "%' "; /*	  * *SEARCH QUERY ** */

    $query_count = $mysqli->query($sql_count);
    $row = $query_count->fetch_row();
    $totalData = $row[0];
    $totalFiltered = $totalData;

    $columns = array(
      0 => 'id',
      1 => 'homepage',
      2 => 'category',
      3 => 'article_title',      
      4 => 'photo',
      5 => 'views',
      6 => 'amp_views',
      7 => 'last_visit',
      8 => 'created'
    );

    /*       * * QUERY ** */
    $data = array();
    $sql = "SELECT * FROM `$tbl_name` WHERE id >0";
    if ($type == 'haitrends') $sql .= ' AND created_utc > 0 ';
    else $sql_count .= ' AND created_utc = 0 ';
    if (!empty($requestData['search']['value']))
      $sql .= " AND article_title LIKE '%" . $requestData['search']['value'] . "%' "; /*	  * *SEARCH QUERY ** */

    $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "  ";
    $query = $mysqli->query($sql);

    $created = '';
    while ($row = $query->fetch_array()) {
      $nestedData = array();

      $id              = $row["id"];
      $homepage        = $row["homepage"];
      $photo           = $row["photo"];
      // $category        = getTableAttr("category", $tbl_prefix . 'article_categories', $row["category"]);
      $category        = $row["category"];
      $article_title   = $row["article_title"];
      $slug            = $row["slug"];
      $link_slug       = '<a href="' . $base_url . '/article/' . $slug . '" target="_blank">' . $slug . '</a>';
      $views           = $row["views"];
      $amp_views       = $row["amp_views"];
      $last_visit      = $row["last_visit"];
      $publish         = $row["publish"];

      if ($publish == 1)
        $nestedData[] = drawPublishedEditDelete($module, $id, 1, 1, 1); //Publish, Edit, Delete
      else
        $nestedData[] = drawNotPublishedEditDelete($module, $id, 1, 1, 1); //Publish, Edit, Delete


      // ONLY ARTICLE WITH PHOTO CAN SET FOR DISPLAY ON HOMEPAGE
      if ($homepage == 1) {
        $homepage = '<a href="listing_' . $module . '.php?action=nohome_' . $module . '&tbl=' . $tbl . '&id=' . $id . '"><span class="label label-warning">Home</span></a>';
      } else {
        if (!empty($photo)) {
          $homepage = '<a href="listing_' . $module . '.php?action=home_' . $module . '&tbl=' . $tbl . '&id=' . $id . '"><span class="label label-warning">No</span></a>';
        } else {
          $homepage = '';
        }
      }

      $nestedData[] = $homepage;

      // PROCESS MULTIPLE CATEGORIES
			$display_categories = '';
			 if ($category) {
          $categories = explode(',', $category);
          
          $category_slug = '';
          $category_name = '';
          foreach ($categories as $category_id) {
            $category_slug = getTableAttr("slug", $tbl_prefix . 'article_categories', $category_id);
            $category_name = getTableAttr("category", $tbl_prefix . 'article_categories', $category_id);
            
            $display_categories .= '<a href="' . $base_url . '/articles/' . $category_slug . '" target="_blank"><span class="label label-info">'. $category_name. '</span></a> ';
          } //foreach
				
      }
      $nestedData[] = $display_categories;

      $nestedData[] = '<strong>' . htmlentities($article_title) . '</strong><br />' . $link_slug;

      if (!empty($photo) && $photo != 'noimage.png')
        $nestedData[] = display_thumb($photo, 'articles', $width = 200, $height = 154);
      else
        $nestedData[] = '';
    
      $nestedData[] = $views;
      $nestedData[] = '<span class="label label-success">'.$amp_views.'</span>';
      $nestedData[] = date("M j, Y h:i a", strtotime($last_visit));

      $nestedData[] = date("M j, Y", strtotime($row["created"]));

      // if ($publish == 0)
      //  $nestedData[] = '<span class="label label-danger">Not Published</span>';
      // else
      //  $nestedData[] = '<span class="label label-success">Published</span>';

      $data[] = $nestedData;
    }
    break;

    ///////// LISTING /////////
    case 'listing_project_categories':

    $requestData = $_REQUEST;
    /** COUNT * */
    $sql_count = "SELECT count(id) FROM `$tbl_name` WHERE id > 0 ";
    if (!empty($requestData['search']['value']))
      $sql_count .= " AND category LIKE '%" . $requestData['search']['value'] . "%' "; /*	  * *SEARCH QUERY ** */

    $query_count = $mysqli->query($sql_count);
    $row = $query_count->fetch_row();
    $totalData = $row[0];
    $totalFiltered = $totalData;

    $columns = array(
      0 => 'id',
      1 => 'photo',
      2 => 'category',
      3 => 'slug',
      4 => 'created'
    );

    /*       * * QUERY ** */
    $data = array();
    $sql = "SELECT * FROM `$tbl_name` WHERE id >0";
    if (!empty($requestData['search']['value']))
      $sql .= " AND category LIKE '%" . $requestData['search']['value'] . "%' "; /*	  * *SEARCH QUERY ** */

    $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "  ";
    $query = $mysqli->query($sql);

    $created = '';
    while ($row = $query->fetch_array()) {
      $nestedData = array();

      $id              = $row["id"];
      $photo           = $row["photo"];
      $category        = $row["category"];
      $slug            = $row["slug"];
      $publish         = $row["publish"];

      if ($publish == 1)
        $nestedData[] = drawPublishedEditDelete($module, $id, 1, 1, 1); //Publish, Edit, Delete
      else
        $nestedData[] = drawNotPublishedEditDelete($module, $id, 1, 1, 1); //Publish, Edit, Delete

      if (!empty($photo) && $photo != 'noimage.png')
      $nestedData[] = display_thumb($photo, 'project_categories', $width = 180, $height = 138);
      else
      $nestedData[] = '';

      $nestedData[] = '<strong>' . $category . '</strong><br />' . '<a href="' . $base_url . '/projects/' . $slug . '" target="_blank">' . $slug . '</a>';
      // $nestedData[] = ;

      $total_projects   = $mysqli->query("SELECT id FROM `" . $GLOBALS['TBL']['PREFIX'] . "projects` WHERE category=$id");
      $nestedData[] = number_format($total_projects->num_rows);

      // $nestedData[] = date("M j, Y", strtotime($row["created"]));

      $data[] = $nestedData;
    }
    break;


    ///////// LISTING /////////
    case 'listing_tags':

    $requestData = $_REQUEST;
    /** COUNT * */
    $sql_count = "SELECT count(id) FROM `$tbl_name` WHERE id > 0 ";
    if (!empty($requestData['search']['value']))
      $sql_count .= " AND tag LIKE '%" . $requestData['search']['value'] . "%' "; /*	  * *SEARCH QUERY ** */

    $query_count = $mysqli->query($sql_count);
    $row = $query_count->fetch_row();
    $totalData = $row[0];
    $totalFiltered = $totalData;

    $columns = array(
      0 => 'id',
      1 => 'tag'
    );

    /*       * * QUERY ** */
    $data = array();
    $sql = "SELECT * FROM `$tbl_name` WHERE id >0";
    if (!empty($requestData['search']['value']))
      $sql .= " AND tag LIKE '%" . $requestData['search']['value'] . "%' "; /*	  * *SEARCH QUERY ** */

    $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "  ";
    $query = $mysqli->query($sql);

    $created = '';
    while ($row = $query->fetch_array()) {
      $nestedData = array();

      $id             = $row["id"];
      $tag            = $row["tag"];
      $publish        = $row["publish"];

      if ($publish == 1)
        $nestedData[] = drawPublishedEditDelete($module, $id, 1, 1, 1); //Publish, Edit, Delete
      else
        $nestedData[] = drawNotPublishedEditDelete($module, $id, 1, 1, 1); //Publish, Edit, Delete

      $nestedData[] = $tag;

      $data[] = $nestedData;
    }
    break;

    ///////// LISTING /////////
    case 'listing_companies':

    $requestData = $_REQUEST;
    /** COUNT * */
    $sql_count = "SELECT count(id) FROM `$tbl_name` WHERE id > 0 ";
    if (!empty($requestData['search']['value']))
      $sql_count .= " AND company_name LIKE '%" . $requestData['search']['value'] . "%' "; /*	  * *SEARCH QUERY ** */

    $query_count = $mysqli->query($sql_count);
    $row = $query_count->fetch_row();
    $totalData = $row[0];
    $totalFiltered = $totalData;

    $columns = array(
      0 => 'id',
      1 => 'company_name'
    );

    /*       * * QUERY ** */
    $data = array();
    $sql = "SELECT * FROM `$tbl_name` WHERE id >0";
    if (!empty($requestData['search']['value']))
      $sql .= " AND company_name LIKE '%" . $requestData['search']['value'] . "%' "; /*	  * *SEARCH QUERY ** */

    $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "  ";
    $query = $mysqli->query($sql);

    $created = '';
    while ($row = $query->fetch_array()) {
      $nestedData = array();

      $id             = $row["id"];
      $company_name   = $row["company_name"];
      $publish        = $row["publish"];

      if ($publish == 1)
        $nestedData[] = drawPublishedEditDelete($module, $id, 1, 1, 0); //Publish, Edit, Delete
      else
        $nestedData[] = drawNotPublishedEditDelete($module, $id, 1, 1, 0); //Publish, Edit, Delete

      $nestedData[] = $company_name;

      $data[] = $nestedData;
    }
    break;
    ///////// LISTING /////////
    case 'listing_cities':

    $requestData = $_REQUEST;
    /** COUNT * */
    $sql_count = "SELECT count(id) FROM `$tbl_name` WHERE id > 0 ";
    if (!empty($requestData['search']['value']))
      $sql_count .= " AND city_name LIKE '%" . $requestData['search']['value'] . "%' "; /*	  * *SEARCH QUERY ** */

    $query_count = $mysqli->query($sql_count);
    $row = $query_count->fetch_row();
    $totalData = $row[0];
    $totalFiltered = $totalData;

    $columns = array(
      0 => 'id',
      1 => 'city_name'
    );

    /*       * * QUERY ** */
    $data = array();
    $sql = "SELECT * FROM `$tbl_name` WHERE id >0";
    if (!empty($requestData['search']['value']))
      $sql .= " AND city_name LIKE '%" . $requestData['search']['value'] . "%' "; /*	  * *SEARCH QUERY ** */

    $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "  ";
    $query = $mysqli->query($sql);

    $created = '';
    while ($row = $query->fetch_array()) {
      $nestedData = array();

      $id             = $row["id"];
      $city_name      = $row["city_name"];
      $publish        = $row["publish"];

      if ($publish == 1)
        $nestedData[] = drawPublishedEditDelete($module, $id, 1, 1, 0); //Publish, Edit, Delete
      else
        $nestedData[] = drawNotPublishedEditDelete($module, $id, 1, 1, 0); //Publish, Edit, Delete

      $nestedData[] = $city_name;

      $data[] = $nestedData;
    }

    break;

    ///////// LISTING /////////
    case 'listing_telecoms':

    $requestData = $_REQUEST;
    /** COUNT * */
    $sql_count = "SELECT count(id) FROM `$tbl_name` WHERE id > 0 ";
    if (!empty($requestData['search']['value']))
      $sql_count .= " AND company_name LIKE '%" . $requestData['search']['value'] . "%' "; /*	  * *SEARCH QUERY ** */

    $query_count = $mysqli->query($sql_count);
    $row = $query_count->fetch_row();
    $totalData = $row[0];
    $totalFiltered = $totalData;

    $columns = array(
      0 => 'id',
      1 => 'company_name'
    );

    /*       * * QUERY ** */
    $data = array();
    $sql = "SELECT * FROM `$tbl_name` WHERE id >0";
    if (!empty($requestData['search']['value']))
      $sql .= " AND company_name LIKE '%" . $requestData['search']['value'] . "%' "; /*	  * *SEARCH QUERY ** */

    $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "  ";
    $query = $mysqli->query($sql);

    $created = '';
    while ($row = $query->fetch_array()) {
      $nestedData = array();

      $id             = $row["id"];
      $company_name   = $row["company_name"];
      $publish        = $row["publish"];

      if ($publish == 1)
        $nestedData[] = drawPublishedEditDelete($module, $id, 1, 1, 0); //Publish, Edit, Delete
      else
        $nestedData[] = drawNotPublishedEditDelete($module, $id, 1, 1, 0); //Publish, Edit, Delete

      $nestedData[] = $company_name;

      $data[] = $nestedData;
    }
    break;
//////////////////////
 ///////// LISTING /////////
    case 'listing_sims_request':

    $requestData = $_REQUEST;
    /** COUNT * */
    $sql_count = "SELECT count(id) FROM `$tbl_name` WHERE id > 0 ";
    if (!empty($requestData['search']['value']))
      $sql_count .= " AND company LIKE '%" . $requestData['search']['value'] . "%' "; /*	  * *SEARCH QUERY ** */

    $query_count = $mysqli->query($sql_count);
    $row = $query_count->fetch_row();
    $totalData = $row[0];
    $totalFiltered = $totalData;

    $columns = array(
      
      0 => 'telecom_name',
      1 => 'company',
      2 => 'quantity'
     
    );

    /*       * * QUERY ** */
    $data = array();
    $sql = "SELECT * FROM `$tbl_name` WHERE id >0";
    if (!empty($requestData['search']['value']))
      $sql .= " AND company LIKE '%" . $requestData['search']['value'] . "%' "; /*	  * *SEARCH QUERY ** */

    $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "  ";
    $query = $mysqli->query($sql);

    $created = '';
    while ($row = $query->fetch_array()) {
      $nestedData = array();

      $id             = $row["id"];
      $company   = $row["company"];
      $publish        = $row["publish"];

      
      $nestedData[] = $company;

      $data[] = $nestedData;
    }
    break;

//////////////////
    ///////// LISTING /////////
  case 'listing_sim_packages':

    $requestData = $_REQUEST;
    /** COUNT * */
    $sql_count = "SELECT count(id) FROM `$tbl_name` WHERE id > 0 ";
    if (!empty($requestData['search']['value']))
    $sql_count .= " AND company_name LIKE '%" . $requestData['search']['value'] . "%' "; /*	  * *SEARCH QUERY ** */

    $query_count = $mysqli->query($sql_count);
    $row = $query_count->fetch_row();
    $totalData = $row[0];
    $totalFiltered = $totalData;

    $columns = array(
      0 => 'id',
      1 => 'package_name',
      2 => 'amount'
    );

    /*       * * QUERY ** */
    $data = array();
    $sql = "SELECT * FROM `$tbl_name` WHERE id >0";
    if (!empty($requestData['search']['value']))
    $sql .= " AND package_name LIKE '%" . $requestData['search']['value'] . "%' "; /*	  * *SEARCH QUERY ** */

    $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "  ";
    $query = $mysqli->query($sql);

    $created = '';
    while ($row = $query->fetch_array()
    ) {
      $nestedData = array();

      $id             = $row["id"];
      $package_name   = $row["package_name"];
      $amount         = $row["amount"];
      $publish        = $row["publish"];

      if ($publish == 1)
      $nestedData[] = drawPublishedEditDelete($module, $id, 1, 1, 0); //Publish, Edit, Delete
      else
      $nestedData[] = drawNotPublishedEditDelete($module, $id, 1, 1, 0); //Publish, Edit, Delete

      $nestedData[] = $package_name;
      $nestedData[] = $amount;

      $data[] = $nestedData;
    }
    break;



    ///////// LISTING /////////
    case 'listing_sims':

    $requestData = $_REQUEST;
    /** COUNT * */
    $sql_count = "SELECT count(id) FROM `$tbl_name` WHERE id > 0 ";
    if (!empty($requestData['search']['value']))
      $sql_count .= " AND sim_number LIKE '%" . $requestData['search']['value'] . "%' "; /*	  * *SEARCH QUERY ** */

    $query_count = $mysqli->query($sql_count);
    $row = $query_count->fetch_row();
    $totalData = $row[0];
    $totalFiltered = $totalData;

    $columns = array(
      0 => 'id',
      1 => 'telecom_name',
      2 => 'sim_number',
      3 => 'Employee Name',
      4 => 'package_name',
      5 => 'amount'
    );

    /*       * * QUERY ** */
    $data = array();
    $sql = "SELECT * FROM `$tbl_name` WHERE id >0";
    if (!empty($requestData['search']['value']))
      $sql .= " AND sim_number LIKE '%" . $requestData['search']['value'] . "%' "; /*	  * *SEARCH QUERY ** */

    $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "  ";
    $query = $mysqli->query($sql);

    $created = '';
    while ($row = $query->fetch_array()) {
      $nestedData = array();

      $id             = $row["id"];
      $telecom_name   = $row["telecom_name"];
      $package_name   = $row["package_name"];
      $sim_number     = $row["sim_number"];
      
      $amount         = $row["amount"];
      $publish        = $row["publish"];
      $company='';
      if ($publish == 1)
        $nestedData[] = drawPublishedEditDelete($module, $id, 0, 1, 0); //Publish, Edit, Delete
      else
        $nestedData[] = drawNotPublishedEditDelete($module, $id, 0, 1, 0); //Publish, Edit, Delete

      $nestedData[] = ucwords($telecom_name);
      
      $vs=getTableAttrWhere("id", $tbl_prefix . 'employees_sims','sim_id', $id);
      if($vs<0 || $vs==null){
      $nestedData[] = '<strong>'. $sim_number. '</strong> &nbsp; &nbsp; <a href="assign_sim.php?id='.$id.'"><span class="label label-success">ASSIGN</span></a>';
    }
      else {
           $employee_id=getTableAttrWhere("employee_id", $tbl_prefix . 'employees_sims','sim_id', $id);
           
           $employee_name=getTableAttrWhere("employee_name", $tbl_prefix . 'employees','id', $employee_id);
           $companyname=getTableAttrWhere("company", $tbl_prefix . 'employees','id', $employee_id);
         $company= $employee_name.' ('.getTableAttr("company_name", $tbl_prefix . 'companies', $companyname).')';
          
          
          
          $nestedData[] = '<strong>'. $sim_number. '</strong> &nbsp; &nbsp; <a href="#"><span class="label label-warning">ASSIGNED to </span></a>';}
       $nestedData[] =  $company;
      $nestedData[] = getTableAttr("package_name", $tbl_prefix . 'sim_packages', $package_name);      
      $nestedData[] = $amount;

      $data[] = $nestedData;
    }
    break;

    ///////// LISTING /////////
    case 'listing_employees':

    $requestData = $_REQUEST;
    /** COUNT * */
    $sql_count = "SELECT count(id) FROM `$tbl_name` WHERE id > 0 ";
    if (!empty($requestData['search']['value']))
      $sql_count .= " AND employee_name LIKE '%" . $requestData['search']['value'] . "%' "; /*	  * *SEARCH QUERY ** */

    $query_count = $mysqli->query($sql_count);
    $row = $query_count->fetch_row();
    $totalData = $row[0];
    $totalFiltered = $totalData;

    $columns = array(
      0 => 'id',
      1 => 'employee_name',
      2 => 'company',
      3 => 'staff_id',      
      4 => 'city',
      5 => 'job_title',
       6 => 'Sim',
        7 => 'Mobile_Company',
         8 => 'Package',
      9 => 'address'
    );

    /*       * * QUERY ** */
    $data = array();
    $sql = "SELECT * FROM `$tbl_name` WHERE id >0";
    if (!empty($requestData['search']['value']))
      $sql .= " AND employee_name LIKE '%" . $requestData['search']['value'] . "%' "; /*	  * *SEARCH QUERY ** */

    $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "  ";
    $query = $mysqli->query($sql);

    $created = '';
    while ($row = $query->fetch_array()) {
      $nestedData = array();

      $id             = $row["id"];
      $employee_name  = $row["employee_name"];
      $company        = $row["company"];
      $staff_id       = $row["staff_id"];      
      $city           = $row["city"];
      $job_title      = $row["job_title"];
      $address        = $row["address"];      
      $publish        = $row["publish"];
      $sim_id=getTableAttrWhere("sim_id", $tbl_prefix . 'employees_sims','employee_id', $row["id"]); 
       $sim=getTableAttr("sim_number", $tbl_prefix . 'sims',$sim_id);
       $package_name=getTableAttr("package_name", $tbl_prefix . 'sims',$sim_id);
      $company_name=getTableAttr("telecom_name", $tbl_prefix . 'sims',$sim_id);
    
      $package=getTableAttr("package_name", $tbl_prefix . 'sim_packages', $package_name); 

      if ($publish == 1)
        $nestedData[] = drawPublishedEditDelete($module, $id, 1, 1, 1); //Publish, Edit, Delete
      else
        $nestedData[] = drawNotPublishedEditDelete($module, $id, 1, 1, 1); //Publish, Edit, Delete

      $nestedData[] = '<h6><strong><a href="assign_sim.php?id='. $id. '">'.$employee_name. '</a></strong></h6>';
      $nestedData[] = getTableAttr("company_name", $tbl_prefix . 'companies', $company);
      $nestedData[] = ''. $staff_id. '';      
      $nestedData[] = getTableAttr("city_name", $tbl_prefix . 'cities', $city);
      $nestedData[] = $job_title;
       $nestedData[] ='<strong>'. $sim.'</strong>';
        $nestedData[] =  ucfirst($company_name);
         $nestedData[] =  ucfirst($package);
      $nestedData[] = $address;
      

      $data[] = $nestedData;
    }
    break;

    ///////// LISTING /////////
    case 'listing_projects':

    $requestData = $_REQUEST;
    /** COUNT * */
    $sql_count = "SELECT count(id) FROM `$tbl_name` WHERE id > 0 ";
    if (!empty($requestData['search']['value']))
      $sql_count .= " AND project_title LIKE '%" . $requestData['search']['value'] . "%' "; /*	  * *SEARCH QUERY ** */

    $query_count = $mysqli->query($sql_count);
    $row = $query_count->fetch_row();
    $totalData = $row[0];
    $totalFiltered = $totalData;

    $columns = array(
      0 => 'id',
      1 => 'homepage',
      2 => 'category',
      3 => 'project_title',      
      4 => 'photo',
      5 => 'views',
      6 => 'amp_views',
      7 => 'last_visit',
      8 => 'created'
    );

    /*       * * QUERY ** */
    $data = array();
    $sql = "SELECT * FROM `$tbl_name` WHERE id >0";
    if (!empty($requestData['search']['value']))
      $sql .= " AND project_title LIKE '%" . $requestData['search']['value'] . "%' "; /*	  * *SEARCH QUERY ** */

    $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "  ";
    $query = $mysqli->query($sql);

    $created = '';
    while ($row = $query->fetch_array()) {
      $nestedData = array();

      $id              = $row["id"];
      $homepage        = $row["homepage"];
      $photo           = $row["photo"];
      $category        = $row["category"];
      $project_title   = $row["project_title"];
      $slug            = $row["slug"];
      $link_slug       = '<a href="' . $base_url . '/project/' . $slug . '" target="_blank">' . $slug . '</a>';
      $views           = $row["views"];
      $amp_views       = $row["amp_views"];
      $last_visit      = $row["last_visit"];
      $publish         = $row["publish"];

      if ($publish == 1)
        $nestedData[] = drawPublishedEditDelete($module, $id, 1, 1, 1); //Publish, Edit, Delete
      else
        $nestedData[] = drawNotPublishedEditDelete($module, $id, 1, 1, 1); //Publish, Edit, Delete


      // ONLY PROJECT WITH PHOTO CAN SET FOR DISPLAY ON HOMEPAGE
      if ($homepage == 1) {
        $homepage = '<a href="listing_' . $module . '.php?action=nohome_' . $module . '&tbl=' . $tbl . '&id=' . $id . '"><span class="label label-warning">Home</span></a>';
      } else {
        if (!empty($photo)) {
          $homepage = '<a href="listing_' . $module . '.php?action=home_' . $module . '&tbl=' . $tbl . '&id=' . $id . '"><span class="label label-warning">No</span></a>';
        } else {
          $homepage = '';
        }
      }

      $nestedData[] = $homepage;

      // // PROCESS MULTIPLE CATEGORIES
			$display_categories = '';
			 if ($category) {
          $categories = explode(',', $category);
          
          $category_slug = '';
          $category_name = '';
          foreach ($categories as $category_id) {
            $category_slug = getTableAttr("slug", $tbl_prefix . 'project_categories', $category_id);
            $category_name = getTableAttr("category", $tbl_prefix . 'project_categories', $category_id);

            $display_categories .= '<a href="' . $base_url . '/projects/' . $category_slug . '" target="_blank"><span class="label label-info">' . $category_name . '</span></a> ';
          } //foreach
				
      }
      $nestedData[] = $display_categories;

      $nestedData[] = '<strong>' . htmlentities($project_title) . '</strong><br />' . $link_slug;

      if (!empty($photo) && $photo != 'noimage.png')
        $nestedData[] = display_thumb($photo, 'projects', $width = 180, $height = 105);
      else
        $nestedData[] = '';
    
      $nestedData[] = $views;
      $nestedData[] = '<span class="label label-success">'.$amp_views.'</span>';
      $nestedData[] = date("M j, Y h:i a", strtotime($last_visit));

      $nestedData[] = date("M j, Y", strtotime($row["created"]));

      if ($publish == 0)
       $nestedData[] = '<span class="label label-danger">Not Published</span>';
      else
       $nestedData[] = '<span class="label label-success">Published</span>';

      $data[] = $nestedData;
    }
    break;

    ///////// LISTING /////////
    case 'listing_article_categories':

    $requestData = $_REQUEST;
    /** COUNT * */
    $sql_count = "SELECT count(id) FROM `$tbl_name` WHERE id > 0 ";
    if (!empty($requestData['search']['value']))
      $sql_count .= " AND category LIKE '%" . $requestData['search']['value'] . "%' "; /*	  * *SEARCH QUERY ** */

    $query_count = $mysqli->query($sql_count);
    $row = $query_count->fetch_row();
    $totalData = $row[0];
    $totalFiltered = $totalData;

    $columns = array(
      0 => 'id',
      1 => 'category',
      2 => 'slug',
      3 => 'created'
    );

    /*       * * QUERY ** */
    $data = array();
    $sql = "SELECT * FROM `$tbl_name` WHERE id >0";
    if (!empty($requestData['search']['value']))
      $sql .= " AND category LIKE '%" . $requestData['search']['value'] . "%' "; /*	  * *SEARCH QUERY ** */

    $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "  ";
    $query = $mysqli->query($sql);

    $created = '';
    while ($row = $query->fetch_array()) {
      $nestedData = array();

      $id              = $row["id"];
      $category        = $row["category"];
      $slug            = $row["slug"];
      $publish         = $row["publish"];

      if ($id == 1) { // ID:1 -> Page
        $nestedData[] = '';
      } else {
        if ($publish == 1)
          $nestedData[] = drawPublishedEditDelete($module, $id, 1, 1, 1); //Publish, Edit, Delete
        else
          $nestedData[] = drawNotPublishedEditDelete($module, $id, 1, 1, 1); //Publish, Edit, Delete
      }

      $nestedData[] = '<strong>' . $category . '</strong><br />' . '<a href="' . $base_url . '/articles/' . $slug . '" target="_blank">' . $slug . '</a>';
      // $nestedData[] = ;

      $total_articles   = $mysqli->query("SELECT id FROM `" . $GLOBALS['TBL']['PREFIX'] . "articles` WHERE category=$id");
      $nestedData[] = number_format($total_articles->num_rows);

      // $nestedData[] = date("M j, Y", strtotime($row["created"]));

      $data[] = $nestedData;
    }
    break;

    ///////// LISTING /////////
    case 'listing_services':

    $requestData = $_REQUEST;
    /** COUNT * */
    $sql_count = "SELECT count(id) FROM `$tbl_name` WHERE id > 0 ";
    if (!empty($requestData['search']['value']))
      $sql_count .= " AND service_name LIKE '%" . $requestData['search']['value'] . "%' "; /*	  * *SEARCH QUERY ** */

    $query_count = $mysqli->query($sql_count);
    $row = $query_count->fetch_row();
    $totalData = $row[0];
    $totalFiltered = $totalData;

    $columns = array(
      0 => 'id',
      1 => 'photo',
      2 => 'service_name',
      3 => 'featured',
      4 => 'views',
      5 => 'amp_views',
      6 => 'last_visit',
      7 => 'created'
    );

    /*       * * QUERY ** */
    $data = array();
    $sql = "SELECT * FROM `$tbl_name` WHERE id >0";
    if (!empty($requestData['search']['value']))
      $sql .= " AND service_name LIKE '%" . $requestData['search']['value'] . "%' "; /*	  * *SEARCH QUERY ** */

    $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "  ";
    $query = $mysqli->query($sql);

    $created = '';
    while ($row = $query->fetch_array()) {
      $nestedData = array();

      $id = $row["id"];
      $photo           = $row["photo"];
      $service_name    = $row["service_name"];
      $featured        = $row["featured"];
      $summary         = $row["summary"];
      $slug            = $row["slug"];
      $link_slug       = '<a href="' . $base_url . '/service/' . $slug . '" target="_blank">' . $slug . '</a>';
      $views           = $row["views"];
      $amp_views       = $row["amp_views"];
      $last_visit      = $row["last_visit"];
      $publish         = $row["publish"];
      $link            = '<a href="' . $base_url . '/service/' . $slug . '" target="_blank">&#128065;</a>';

      if ($publish == 1)
        $nestedData[] = drawPublishedEditDelete($module, $id, 1, 1, 1); //Publish, Edit, Delete
      else
        $nestedData[] = drawNotPublishedEditDelete($module, $id, 1, 1, 1); //Publish, Edit, Delete

      if (!empty($photo) && $photo != 'noimage.png')
        $nestedData[] = display_thumb($photo, 'services', $width = 170, $height = 135);
      else
        $nestedData[] = '';
      
        if ($publish == 0) {
        $nestedData[] = '<span style="color:red;">' . htmlentities($service_name) . '</span><br />' . $link_slug;
      } else {
        $nestedData[] = '<strong>' . htmlentities($service_name) . '</strong><br />' . $link_slug;
      }

      // ONLY ARTICLE WITH PHOTO CAN SET FOR DISPLAY ON HOMEPAGE
      if ($featured == 1) {
        $featured = '<a href="listing_' . $module . '.php?action=nofeatured_' . $module . '&tbl=' . $tbl . '&id=' . $id . '"><span class="label label-warning">Featured</span></a>';
      } else {
          $featured = '<a href="listing_' . $module . '.php?action=featured_' . $module . '&tbl=' . $tbl . '&id=' . $id . '"><span class="label label-warning">No</span></a>';
      }

      $nestedData[] = $featured;
      // $nestedData[] = $summary;
      $nestedData[] = $views;
      $nestedData[] = '<span class="label label-success">' . $amp_views . '</span>';
      
      $nestedData[] = date("M j, Y h:i a", strtotime($last_visit));

      $nestedData[] = date("M j, Y", strtotime($row["created"]));

      // if ($publish == 0)
      //  $nestedData[] = '<span class="label label-danger">Not Published</span>';
      // else
      //  $nestedData[] = '<span class="label label-success">Published</span>';

      $data[] = $nestedData;
    }
    break;
    
    ///////// LISTING /////////
    case 'listing_service_tabs':

    $requestData = $_REQUEST;
    /** COUNT * */
    $sql_count = "SELECT count(id) FROM `$tbl_name` WHERE id > 0 ";
    if (!empty($requestData['search']['value']))
      $sql_count .= " AND service_tab_name LIKE '%" . $requestData['search']['value'] . "%' "; /*	  * *SEARCH QUERY ** */

    $query_count = $mysqli->query($sql_count);
    $row = $query_count->fetch_row();
    $totalData = $row[0];
    $totalFiltered = $totalData;

    $columns = array(
      0 => 'id',
      1 => 'flaticon',
      2 => 'photo',
      3 => 'service_tab_name'
    );

    /*       * * QUERY ** */
    $data = array();
    $sql = "SELECT * FROM `$tbl_name` WHERE id >0";
    if (!empty($requestData['search']['value']))
      $sql .= " AND service_tab_name LIKE '%" . $requestData['search']['value'] . "%' "; /*	  * *SEARCH QUERY ** */

    $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "  ";
    $query = $mysqli->query($sql);

    $created = '';
    while ($row = $query->fetch_array()) {
      $nestedData = array();

      $id                 = $row["id"];
      $flaticon           = $row["flaticon"];
      $photo              = $row["photo"];
      $service_tab_name   = $row["service_tab_name"];
      $publish            = $row["publish"];

      if ($publish == 1)
        $nestedData[] = drawPublishedEditDelete($module, $id, 1, 1, 1); //Publish, Edit, Delete
      else
        $nestedData[] = drawNotPublishedEditDelete($module, $id, 1, 1, 1); //Publish, Edit, Delete

      $nestedData[] = $flaticon;

      if (!empty($photo) && $photo != 'noimage.png')
        $nestedData[] = display_thumb($photo, 'service_tabs', $width = 150, $height = 150);
      else
        $nestedData[] = '';
        
      
        if ($publish == 0) {
        $nestedData[] = '<span style="color:red;">' . htmlentities($service_tab_name) . '</span>';
      } else {
        $nestedData[] = '<strong>' . htmlentities($service_tab_name) . '</strong>';
      }

      $data[] = $nestedData;
    }
    break;

  ///////// LISTING /////////
  case 'email_targets':

  $module   = 'companies';
  $tbl_name = $tbl_prefix . $module;

  $requestData = $_REQUEST;
  /** COUNT * */
  $sql_count = "SELECT count(id) FROM `$tbl_name` WHERE id > 0 ";
  if (!empty($requestData['search']['value']))
   $sql_count .= " AND company_name LIKE '%" . $requestData['search']['value'] . "%' "; /*	  * *SEARCH QUERY ** */

  $query_count    = $mysqli->query($sql_count);
  $row            = $query_count->fetch_row();
  $totalData      = $row[0];
  $totalFiltered  = $totalData;

  $columns = array(
    0 => 'id',
    1 => 'views',
    2 => 'amp_views',
    3 => 'last_visit',
    4 => 'company_name',
    5 => 'company_category',
    6 => 'email',
    7 => 'created',
    7 => 'publish'
  );

  /*       * * QUERY ** */
  $data = array();
  $sql = "SELECT id, views, amp_views, last_visit, company_name, slug, company_category, email, created, publish FROM `$tbl_name` WHERE id >0 AND verified=0 ";
  if (!empty($requestData['search']['value']))
    $sql .= " AND company_name LIKE '" . $requestData['search']['value'] . "%' "; /*	  * *SEARCH QUERY ** */

  $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "  ";
  $query = $mysqli->query($sql);

  $created = '';
  while ($row = $query->fetch_array()) {
    $nestedData = array();

    $id = $row["id"];

    $company_name        = $row["company_name"];
    $slug                = $row["slug"];
    $link_slug           = '<a href="' . $base_url . '/' . $slug . '" target="_blank">' . $slug . '</a>';

    $company_category    = $row["company_category"];
    $email               = $row["email"];
    $views               = $row["views"];
    $amp_views           = $row["amp_views"];
    $last_visit          = $row["last_visit"];
    $created             = $row["created"];
    $publish             = $row["publish"];

    //Publish, Edit, Delete
    if ($publish == 1) {
      $nestedData[] = drawPublishedEditDelete($module, $id, 1, 0, 1) . ' <a href="companies.php?action=edit_companies&tbl=' . $tbl . '&id=' . $id . '" title="Edit"><i class=" icon-pencil3"></i></a>';
    } else {
      $nestedData[] = drawPublishedEditDelete($module, $id, 1, 0, 1) . ' <a href="companies.php?action=edit_companies&tbl=' . $tbl . '&id=' . $id . '" title="Edit"><i class=" icon-pencil3"></i></a>';
    }

    $nestedData[] = $views;
    $nestedData[] = $amp_views;
    $nestedData[] = date("M j, Y h:i a", strtotime($last_visit));

    if ($publish == 0) {
      $nestedData[] = '<span style="color:red;">' . $company_name . '</span>';
    } else {
      $nestedData[] = '<strong>' . $company_name . '</strong><br />' . $link_slug;
    }

    // $nestedData[] = $company_category;
    // $nestedData[] = $email;
    $nestedData[] = date("M j, Y", strtotime($row["created"]));

    if ($publish == 0)
      $nestedData[] = '<span class="label label-danger">Not Verified</span>';
    else
      $nestedData[] = '<span class="label label-success">Verified</span>';

    $data[] = $nestedData;
    }
    break;

    ///////// LISTING /////////
  case 'listing_admins':

    $requestData = $_REQUEST;

    /** COUNT * */
    $sql_count = "SELECT count(id) FROM `$tbl_name` WHERE id > 0 ";
    if (!empty($requestData['search']['value']))
      $sql_count .= " AND username LIKE '" . $requestData['search']['value'] . "%' "; /*	  * *SEARCH QUERY ** */
    $query_count = $mysqli->query($sql_count);
    $row = $query_count->fetch_row();
    $totalData = $row[0];
    $totalFiltered = $totalData;


    $columns = array(
      0 => 'id',
      1 => 'username',
      2 => 'type',
      3 => 'full_name',
      4 => 'email',
      5 => 'mobile'
    );

    /*       * * QUERY ** */
    $data = array();
    $sql = "SELECT * FROM `$tbl_name` WHERE id >0 ";
    if (!empty($requestData['search']['value']))
      $sql .= " AND username LIKE '" . $requestData['search']['value'] . "%' "; /*	  * *SEARCH QUERY ** */
    $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "  ";
    $query = $mysqli->query($sql);

    while ($row = $query->fetch_array()) {
      $nestedData = array();

      $id         = $row["id"];


      if ($row["type"] == 'superadmin') $type = '<span class="green">' . $row["type"] . '</span>';
      else                              $type = '<span>' . $row["type"] . '</span>';

      $active_inactive = '';
      if ($row["active"] == 1)        $active_inactive = '<span class="label label-success">Active</span> &nbsp; &nbsp; &nbsp;';
      else if ($row["active"] == 0)   $active_inactive = '<span class="label label-danger">Inactive</span> &nbsp;';

      $nestedData[] = drawNotPublishedEditDelete($module, $id, 0, 1, 1); //Publish, Edit, Delete

      $nestedData[] = $active_inactive . ' <strong>' . getUsernameFromID($row["id"], $tbl_prefix . 'admins') . '</strong>';
      $nestedData[] = $type;
      $nestedData[] = $row["full_name"];
      $nestedData[] = $row["email"];
      $nestedData[] = $row["mobile"];

      $nestedData[] = timezone($row["created"]);

      $data[] = $nestedData;
    }
    break;

    ///////// LISTING /////////
  case 'listing_admin_logs':

    $requestData = $_REQUEST;

    /** COUNT * */
    $sql_count = "SELECT count(id) FROM `$tbl_name` WHERE id > 0 ";
    if (!empty($requestData['search']['value']))
      $sql_count .= " AND ip LIKE '" . $requestData['search']['value'] . "%' "; /*	  * *SEARCH QUERY ** */
    $query_count = $mysqli->query($sql_count);
    $row = $query_count->fetch_row();
    $totalData = $row[0];
    $totalFiltered = $totalData;

    $columns = array(
      0 => 'created',
      1 => 'admin_id',
      2 => 'ip'
    );
    /*       * * QUERY ** */
    $data = array();
    $sql = "SELECT * FROM `$tbl_name` WHERE id >0 ";
    if (!empty($requestData['search']['value']))
      $sql .= " AND ip LIKE '" . $requestData['search']['value'] . "%' "; /*	  * *SEARCH QUERY ** */
    $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "  LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
    $query = $mysqli->query($sql);

    while ($row = $query->fetch_array()) {
      $nestedData = array();

      $nestedData[] = date("M j, Y g:i a", strtotime($row["created"]));
      $nestedData[] = getUsernameFromID($row["admin_id"], $tbl_prefix . 'admins');
      $nestedData[] = $row["ip"];

      $data[] = $nestedData;
    }
    break;

    ///////// LISTING /////////
  case 'listing_admin_blocks':

    $requestData = $_REQUEST;

    /** COUNT * */
    $sql_count = "SELECT count(id) FROM `$tbl_name` WHERE id >0 ";
    if (!empty($requestData['search']['value']))
      $sql_count .= " AND ip LIKE '" . $requestData['search']['value'] . "%' "; /*	  * *SEARCH QUERY ** */
    $query_count = $mysqli->query($sql_count);
    $row = $query_count->fetch_row();
    $totalData = $row[0];
    $totalFiltered = $totalData;

    $columns = array(
      0 => 'created',
      1 => 'admin_id',
      2 => 'ip'
    );
    /*       * * QUERY ** */
    $data = array();
    $sql = "SELECT * FROM `$tbl_name` WHERE id >0 ";
    if (!empty($requestData['search']['value']))
      $sql .= " AND ip LIKE '" . $requestData['search']['value'] . "%' "; /*	  * *SEARCH QUERY ** */
    $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "  LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
    $query = $mysqli->query($sql);

    while ($row = $query->fetch_array()) {
      $nestedData = array();
      $id           = $row['id'];
      $nestedData[] = date("M j,Y g:i a", strtotime($row["created"]));
      $nestedData[] = getUsernameFromID($row["admin_id"], $tbl_prefix . 'admins'); //
      $nestedData[] = $row["ip"];

      $data[] = $nestedData;
    }
    break;

    ///////// LISTING /////////
  case 'listing_admin_activities':

    $requestData = $_REQUEST;

    /** COUNT * */
    $sql_count = "SELECT count(id) FROM `$tbl_name` WHERE id >0 ";
    $query_count = $mysqli->query($sql_count);
    $row = $query_count->fetch_row();
    $totalData = $row[0];
    $totalFiltered = $totalData;

    $columns = array(
      0 => 'id',
      1 => 'ip',
      2 => 'created'
    );
    /*       * * QUERY ** */
    $data = array();
    $sql = "SELECT * FROM `$tbl_name` WHERE id >0 ";
    $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "  LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
    $query = $mysqli->query($sql);

    while ($row = $query->fetch_array()) {
      $nestedData = array();
      $id = $row['id'];
      $nestedData[] = '<a href="listing_' . $module . '.php?action=delete_' . $module . '&id=' . $id . '" title="Remove"><i class="splashy-document_letter_remove"></i></a>';

      $nestedData[] = getUsernameFromID($row["created_by"], $tbl_prefix . 'admins'); //
      $nestedData[] = $row["ip"];
      $nestedData[] = $row["activity"];
      $nestedData[] = timezone($row["created"]);

      $data[] = $nestedData;
    }
    break;
} //switch



$json_data = array(
  "draw" => intval($requestData['draw']), // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
  "recordsTotal" => intval($totalData), // total number of records
  "recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
  "data" => $data   // total data array
);

echo json_encode($json_data);  // send data as json format
