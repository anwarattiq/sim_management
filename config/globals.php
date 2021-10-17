<?php

  /** commenting style Make sure that the WordPress bootstrap has run before continuing. */

  /*
   * commenting style  This file is part of the Symfony package.
   *
   * (c) Fabien Potencier <fabien@symfony.com>
   *
   * For the full copyright and license information, please view the LICENSE
   * file that was distributed with this source code.
   */

  /**
   * commenting style  Sets a callback that is called when the write buffer becomes empty.
   */


	// Display All Errors
	// error_reporting(E_ALL);
	// ini_set('display_errors', 1);


	// SET SERVER TIMEZONE
	// date_default_timezone_set('Asia/Dubai');


	/*****************************
  @@@ Local URL / Remote URL @@@
  ******************************/
  function isRemote() {
    if ( preg_match('/localhost/', $_SERVER['HTTP_HOST']) || preg_match('/127.0.0.1/', $_SERVER['HTTP_HOST']) )
			return false;

		else
			return true;
  }

	$local_live = 'local';
	if (isRemote()){ $local_live = 'live'; }


	//=========================================
	// check if http or https
	//=========================================
	if ( (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443) {
		$starting_ht_protocol = 'https://';

	} else {
		$starting_ht_protocol = 'http://';
	}


	// SET LIVE PATHS
	if (isRemote()) {
		// $base_url 				= $starting_ht_protocol.'www.thewebai.com/mobiletracker';
		// $page_url         = $starting_ht_protocol. $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		// $admin_base_url 	= $starting_ht_protocol. 'www.thewebai.com/mobiletracker';
		$base_url 				= $starting_ht_protocol.'www.shopinitool.com/sim';
		$page_url         = $starting_ht_protocol. $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		$admin_base_url 	= $starting_ht_protocol. 'www.shopinitool.com/sim';

    // SET BASE URL in GLOBAL
    $GLOBALS['SETTINGS']['BASE_URL']	= $base_url;;

	// SET LOCAL PATHS
	} else {
		$base_url 				= $starting_ht_protocol.'127.0.0.1/sim';
		$page_url         = $starting_ht_protocol. $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		$admin_base_url 	= $starting_ht_protocol. '127.0.0.1/sim';

    // SET BASE URL in GLOBAL
    $GLOBALS['SETTINGS']['BASE_URL']	= $base_url;;
	}

	// GLOBAL SETTINGS
	$GLOBALS['PHOTO']['MAX_UPLOAD_SIZE'] = '5'; //in MB
	$GLOBALS['ADMIN']['IMAGESIZE'] = '(400px x 300px)';
	$modified	= date("Y-m-d H:i:s");


	//******* GET CURRENT PAGE *******//
	$current_url = $_SERVER['PHP_SELF'];
	$current_page_url = explode("/", $current_url);
	for ($i_page=0; $i_page<count($current_page_url); $i_page++){
		$current_page = $current_page_url[$i_page];
	}


  /**********************
  @@@ show_breadcrumb @@@
  ***********************/
  function show_breadcrumb($breads){

    $counter  = 2;
    $base_url = $GLOBALS['SETTINGS']['BASE_URL'];

    $breadcrumb ='  <ul itemscope itemtype="https://schema.org/BreadcrumbList">
                      <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                        <a itemtype="https://schema.org/Thing" itemprop="item" href="'.$base_url.'">
                          <span itemprop="name">Home</span>
                        </a>
                        <meta itemprop="position" content="1" />
                      </li>';

              // EXTRACT Breadcrumb Array
              if (!empty($breads)){
              foreach ($breads as $url => $text){
                if (!empty($url) && !empty($text) ){
                  $breadcrumb .= '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                                    <a itemtype="https://schema.org/Thing" itemprop="item" href="'.$base_url.'/'.$url.'">
                                      <span itemprop="name">'.$text.'</span>
                                    </a>
                                    <meta itemprop="position" content="'.$counter++.'" />
                                  </li>';
                }
              } // foreach
              } // end if

    $breadcrumb .='</ul>';

    return $breadcrumb;
  }


	/*******************
  @@@ loading_time @@@
  ********************/
  function loading_time($start_page_time){
    $time = microtime();
    $time = explode(' ', $time);
    $time = $time[1] + $time[0];
    $finish = $time;
    $total_time = round(($finish - $start_page_time), 3);

    return '<small><em>'.$total_time.' sec</em></small>';
  }

/*******************
  @@@ Reading time @@@
 ********************/

  function display_read_time($content)
  {
    // $content = get_post_field('post_content', $post->ID);
    $count_words = str_word_count(strip_tags($content));

    $read_time = ceil($count_words / 250);

    // $prefix = '<span class="rt-prefix">🕑 </span>';

    if ($read_time == 1) {
      $suffix = ' min read';
    } else {
      $suffix = ' min read';
    }

    // $read_time_output = $prefix . $read_time . $suffix;
    $read_time_output = $read_time . $suffix;

    return $read_time_output;
  }

  /***************************
  @@@ REMOVE SPECIAL CHARS @@@
  ****************************/
	function removeBS($Str) {
	  $StrArr = str_split($Str); $NewStr = '';
	  foreach ($StrArr as $Char) {
	    $CharNo = ord($Char);
	    if ($CharNo == 163) { $NewStr .= $Char; continue; } // keep £
	    if ($CharNo > 31 && $CharNo < 127) {
	      $NewStr .= $Char;
	    }
	  }
	  return $NewStr;
	}


	/******************
	@@@ MINIFY HTML @@@
	*******************/
	function sanitize_output($buffer) {

    $search = array(
        '/\>[^\S ]+/s',     // strip whitespaces after tags, except space
        '/[^\S ]+\</s',     // strip whitespaces before tags, except space
        '/(\s)+/s',         // shorten multiple whitespace sequences
        '/<!--(.|\s)*?-->/' // Remove HTML comments
    );

    $replace = array(
        '>',
        '<',
        '\\1',
        ''
    );

    $buffer = preg_replace($search, $replace, $buffer);

    return $buffer;
}

	/*****************
  @@@ CLEAN HTML @@@
  ******************/
  function cleanInput($input) {

    $search = array(
      '@<script[^>]*?>.*?</script>@si',   // Strip out javascript
      '@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
      '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
      '@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments
    );

      $output = preg_replace($search, '', $input);
      return $output;
    }

  /***************
  @@@ SANITIZE @@@
  ****************/
  function sanitize($input) {
    if (is_array($input)) {
        foreach($input as $var=>$val) {
            $output[$var] = sanitize($val);
        }
    } else {
        if (get_magic_quotes_gpc()) {
            $input = stripslashes($input);
        }
        $input  = cleanInput($input);
        $output = mysql_real_escape_string($input);
    }
    return $output;
  }

  /**************
  @@@ SLUGIFY @@@
  ***************/
  function slugify_company_categories($text) {

    $text      = strtolower($text);

    $text      = str_ireplace(' &amp; ', '-and-', $text);
    $text      = str_ireplace(' & ', '-and-', $text);
    $text      = str_ireplace(' - ', ' ', $text);
    $text      = str_ireplace('/', ' ', $text);
    $text      = str_ireplace(' , ', ' ', $text);
    $text      = str_ireplace(', ', ' ', $text);
    $text      = str_ireplace(',', ' ', $text);
    $text      = str_ireplace(' (', ' ', $text);
    $text      = str_ireplace(' ( ', ' ', $text);
    $text      = str_ireplace('(', ' ', $text);
    $text      = str_ireplace('( ', ' ', $text);
    $text      = str_ireplace(' ) ', '', $text);

    $text = preg_replace("/[^a-z0-9\s-]/", "", $text);
    $text = trim(preg_replace("/[\s-]+/", " ", $text));
    $text = preg_replace("/\s/", "-", $text);
    $text = $text;

    return $text;
  }

  /**************
  @@@ SLUGIFY @@@
  ***************/
  function slugify($text) {
    // replace non letter or digits by -
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);

    // remove unwanted characters
    $text = preg_replace('~[^-\w]+~', '', $text);

    // trim
    $text = trim($text, '-');

    // remove duplicate -
    $text = preg_replace('~-+~', '-', $text);

    // lowercase
    $text = strtolower($text);

    if (empty($text)) {
      return '';
    }

    return $text;
  }

  // function slugify2($string){
  //       return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string), '-'));
  //   }

  /******************
  @@@ RANDOM DATE @@@
  *******************/
  function rand_date($min_date, $max_date) {
      /* Gets 2 dates as string, earlier and later date.
         Returns date in between them.
      */

      $min_epoch = strtotime($min_date);
      $max_epoch = strtotime($max_date);

      $rand_epoch = rand($min_epoch, $max_epoch);

      // return date('M j, Y', $rand_epoch); //Feb 23, 2011
      return date('Y-m-d', $rand_epoch); //Feb 23, 2011
  }

	/************************
  @@@ GENERATE BIT CODE @@@
  *************************/
	function generateRandomString($length = 10) {
		$characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
				$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}

	/****************************
  @@@ CAPTURE EACH USER HIT @@@
  *****************************/
	function capture_user_activity($page_url, $total_time){

			$mysqli = $GLOBALS['DB']['MSQLI'];
      $ip_address     = $_SERVER['REMOTE_ADDR'];
      $mysqli->query("INSERT INTO `" . $GLOBALS['TBL']['PREFIX'] . "user_activities` (activity, sec, ip_address) VALUES ('".$page_url."', '".$total_time."', '".$ip_address."') ");
  }


	/*******************************
	@@@ CHECK IF MOBILE / DESKTOP @@@
	********************************/
	function isMobileDevice(){
	    $aMobileUA = array(
	        '/iphone/i' => 'iPhone',
	        '/ipod/i' => 'iPod',
	        '/ipad/i' => 'iPad',
	        '/android/i' => 'Android',
	        '/blackberry/i' => 'BlackBerry',
	        '/webos/i' => 'Mobile'
	    );

	    //Return true if Mobile User Agent is detected
	    foreach($aMobileUA as $sMobileKey => $sMobileOS){
	        if( isset($_SERVER['HTTP_USER_AGENT']) && preg_match($sMobileKey, $_SERVER['HTTP_USER_AGENT']) ){
	            return true;
	        }
	    }
	    //Otherwise return false..
	    return false;
	}

	/*************************
  YYYY-MM-DD
              TO
                  DD-MM-YYYY
	**************************/
	function processDateYtoD($date){

		if (!empty($date)){
			$explode_date = $date;
			$new_date = explode('-', $explode_date);
			$final_date = $new_date[2].'-'.$new_date[1].'-'.$new_date[0];

		} else {
			$final_date = '';
		}

		return $final_date;
	}

	/*********************
  DD-MM-YYYY
            TO
              YYYY-MM-DD
	**********************/
	function processDateDtoY($date){

		if (!empty($date)){
			$explode_date = $date;
			$new_date = explode('-', $explode_date);
			$final_date = $new_date[2].'-'.$new_date[1].'-'.$new_date[0];

		} else {
			$final_date = '';
		}

		return $final_date;
	}


	/************************************
 		YYYY-MM-DD 24:30:50
								TO
										DD-MM-YYYY 24:30:50
	*************************************/
	function processDateTimeYtoD($datetime){

		$final_datetime='';

		if (!empty($datetime)){
			$exploded_datetime = explode(' ', $datetime);
			$new_date = explode('-', $exploded_datetime[0]);
			$final_date = $new_date[2].'-'.$new_date[1].'-'.$new_date[0];

			$final_datetime = $final_date.' '.$exploded_datetime[1];

		} else {
			$final_datetime = '';
		}

		return $final_datetime;
	}

	/*************************************
 		DD-MM-YYYY 24:30:50
								TO
										YYYY-MM-DD 24:30:50
	**************************************/
	function processDateTimeDtoY($date){

		if (!empty($datetime)){
			$exploded_datetime = explode(' ', $datetime);
			$new_date = explode('-', $exploded_datetime[0]);
			$final_date = $new_date[2].'-'.$new_date[1].'-'.$new_date[0];

			$final_date = $final_date.' '.$exploded_datetime[1];

		} else {
			$final_datetime = '';
		}

		return $final_datetime;
	}


	/************************************
 		YYYY-MM-DD 24:30:50
								TO
										24:30:50 DD-Month-YYYY
	*************************************/
	function datetime_timedate($datetime){

		$final_datetime='';

		if (!empty($datetime)){
			$exploded_datetime = explode(' ', $datetime);
			$new_date = explode('-', $exploded_datetime[0]);
			$final_date = $new_date[2].'-'.$new_date[1].'-'.$new_date[0];

			$final_datetime = $final_date.' '.$exploded_datetime[1];

		} else {
			$final_datetime = '';
		}

		return $final_datetime;
	}

	/******************
   @@@ getPDFName @@@
	*******************/
	function getPDFName($id, $table_name){

		if (!empty($id)) {
			$mysqli = $GLOBALS['DB']['MSQLI'];
			$result = $mysqli->query("SELECT pdf FROM ".$table_name." WHERE id=$id");
			$row = $result->fetch_array();
					return $row[0];
		}

  }

  /*********************
  @@@ getNextArticle @@@
  **********************/
  function getNextArticle($id)
  {
    if (!empty($id)) {
      $mysqli  = $GLOBALS['DB']['MSQLI'];
    $result    = $mysqli->query("SELECT id FROM `" . $GLOBALS['TBL']['PREFIX'] . "articles` WHERE publish=1 AND id = (SELECT min(id) FROM `" . $GLOBALS['TBL']['PREFIX'] . "articles` WHERE id > $id)");
        $row     = $result->fetch_array();
        if (!empty($row[0]))  return $row[0];
    }
  }

  /*********************
  @@@ getPrevArticle @@@
  **********************/
  function getPrevArticle($id)
  {
    if (!empty($id)) {
      $mysqli  = $GLOBALS['DB']['MSQLI'];
      $result    = $mysqli->query("SELECT id FROM `" . $GLOBALS['TBL']['PREFIX'] ."articles` WHERE publish=1 AND id = (SELECT max(id) FROM `" . $GLOBALS['TBL']['PREFIX'] ."articles` WHERE id < $id)");
        $row     = $result->fetch_array();
        if (!empty($row[0]))  return $row[0];
    }
  }


  /****************************
	@@@ GENERATE COMPANY SLUG @@@
	*****************************/
	function generate_company_slug($company_name){

    $slug = '';
    $mysqli = $GLOBALS['DB']['MSQLI'];

      // GENERATE UNIQUE SLUG //
      $slug = slugify($company_name);

      // CHECK IF DUPLICATE SLUG FOUND
      $result	 	= $mysqli->query("SELECT id FROM `".$GLOBALS['TBL']['PREFIX']."companies` WHERE slug='".$slug."' ");

      // IF SAME SLUG FOUND MAKE THE NEW ONE
      if( ($result->num_rows >= 1)){
        $slug = $slug .'-' . rand(1,99);
      }


    return $slug;
	}

  /****************************
	@@@ GENERATE SERVICE SLUG @@@
	*****************************/
	function generate_service_slug($name){

    $slug = '';
    $mysqli = $GLOBALS['DB']['MSQLI'];

      // GENERATE UNIQUE SLUG //
      $slug = slugify($name);

      // CHECK IF DUPLICATE SLUG FOUND
      $result	 	= $mysqli->query("SELECT id FROM `".$GLOBALS['TBL']['PREFIX']."services` WHERE slug='".$slug."' ");

      // IF SAME SLUG FOUND MAKE THE NEW ONE
      if( ($result->num_rows >= 1)){
        $slug = $slug .'-' . rand(1,99);
      }


    return $slug;
	}

  /****************************
	@@@ GENERATE ARTICLE SLUG @@@
	*****************************/
	function generate_article_slug($article_title){

    $slug = '';
    $mysqli = $GLOBALS['DB']['MSQLI'];

      // GENERATE UNIQUE SLUG //
      $slug = slugify($article_title);

      // CHECK IF DUPLICATE SLUG FOUND
      $result	 	= $mysqli->query("SELECT id FROM `".$GLOBALS['TBL']['PREFIX']. "articles` WHERE slug='".$slug."' ");

      // IF SAME SLUG FOUND MAKE THE NEW ONE
      if( ($result->num_rows >= 1)){
        $slug = $slug .'-' . rand(1,99);
      }


    return $slug;
	}

  /*************************************
	@@@ GENERATE ARTICLE CATEGORY SLUG @@@
  **************************************/
	function generate_article_category_slug($category){

    $slug = '';
    $mysqli = $GLOBALS['DB']['MSQLI'];

      // GENERATE UNIQUE SLUG //
      $slug = slugify($category);

      // CHECK IF DUPLICATE SLUG FOUND
      $result	 	= $mysqli->query("SELECT id FROM `".$GLOBALS['TBL']['PREFIX']. "article_categories` WHERE slug='".$slug."' ");

      // IF SAME SLUG FOUND MAKE THE NEW ONE
      if( ($result->num_rows >= 1)){
        $slug = $slug .'-' . rand(1,99);
      }


    return $slug;
	}
  
  /****************************
	@@@ GENERATE PROJECT SLUG @@@
	*****************************/
	function generate_project_slug($project_title){

    $slug = '';
    $mysqli = $GLOBALS['DB']['MSQLI'];

      // GENERATE UNIQUE SLUG //
      $slug = slugify($project_title);

      // CHECK IF DUPLICATE SLUG FOUND
      $result	 	= $mysqli->query("SELECT id FROM `".$GLOBALS['TBL']['PREFIX']. "projects` WHERE slug='".$slug."' ");

      // IF SAME SLUG FOUND MAKE THE NEW ONE
      if( ($result->num_rows >= 1)){
        $slug = $slug .'-' . rand(1,99);
      }


    return $slug;
	}

  /*************************************
	@@@ GENERATE PROJECT CATEGORY SLUG @@@
  **************************************/
	function generate_project_category_slug($category){

    $slug = '';
    $mysqli = $GLOBALS['DB']['MSQLI'];

      // GENERATE UNIQUE SLUG //
      $slug = slugify($category);

      // CHECK IF DUPLICATE SLUG FOUND
      $result	 	= $mysqli->query("SELECT id FROM `".$GLOBALS['TBL']['PREFIX']. "project_categories` WHERE slug='".$slug."' ");

      // IF SAME SLUG FOUND MAKE THE NEW ONE
      if( ($result->num_rows >= 1)){
        $slug = $slug .'-' . rand(1,99);
      }


    return $slug;
	}


  /********************
   @@@ getSlugByBit @@@
	*********************/
	function getSlugByBit($bit){
			$slug = '';

			$mysqli 		= $GLOBALS['DB']['MSQLI'];
			$result_bit = $mysqli->query("SELECT slug FROM `".$GLOBALS['TBL']['PREFIX']."companies` WHERE bit='".$bit."'  LIMIT 1");
			$row_bit 		= $result_bit->fetch_array();

				$slug 			= $row_bit['slug'];

			return $slug;

	}

	/**********************
   @@@ getBrochurePDF @@@
	***********************/
	function getBrochurePDF($id){

		if (!empty($id)) {
			$mysqli = $GLOBALS['DB']['MSQLI'];
			$result = $mysqli->query("SELECT brochure_pdf FROM mc_brochures WHERE id=$id");
			$row = $result->fetch_array();
					return $row[0];
		}

	}

	/***********************
    @@@ get_photo_name @@@
	************************/
	function get_photo_name($column, $id, $table_name){

    if (!empty($id)) {
			$mysqli  = $GLOBALS['DB']['MSQLI'];
			$result  = $mysqli->query("SELECT $column FROM `".$table_name."` WHERE id=$id");
			$row     = $result->fetch_array();
				if (!empty($row[0]))	return $row[0];
		}
	}

  /********************
   @@@ getPhotoName @@@
	*********************/
	// function getPhotoName($id, $table_name){

	// 	if (!empty($id)) {
  //     $mysqli = $GLOBALS['DB']['MSQLI'];
	// 		$result = $mysqli->query("SELECT photo FROM ".$table_name." WHERE id=$id");
	// 		$row = $result->fetch_array();
	// 			if (!empty($row[0]))	return $row[0];
	// 	}
	// }

	/***********************
   @@@ getMenuIdBySlug @@@
	************************/
	function getMenuIdBySlug($slug_){

		$mysqli = $GLOBALS['DB']['MSQLI'];
		$slug_ 				  = '"'.$mysqli->real_escape_string($slug_).'"';
		$result_menu_id = $mysqli->query("SELECT id FROM `".$GLOBALS['TBL']['PREFIX']."menus` WHERE slug=$slug_");
		$row_menu_id    = $result_menu_id->fetch_array();
			return stripslashes($row_menu_id['id']);
	}

	/***************
   @@@ getMeta @@@
	****************/
	function getMeta($type, $slug){

			$mysqli = $GLOBALS['DB']['MSQLI'];
			$result = $mysqli->query("SELECT ".$type." FROM `".$GLOBALS['TBL']['PREFIX']."slugs` WHERE slug='".$slug."' ");
			$row = $result->fetch_array();
				return $row[0];
	}
	/***************
   @@@ getMeta @@@
	****************/
	function getServiceMeta($type, $category_id){

			$mysqli = $GLOBALS['DB']['MSQLI'];
			$result = $mysqli->query("SELECT ".$type." FROM `".$GLOBALS['TBL']['PREFIX']."service_categories` WHERE id='".$category_id."' ");
			$row = $result->fetch_array();
				return $row[0];
	}

	/***************
   @@@ Summary @@@
	****************/
	function display_summary($chars, $text){
		$summary = substr($text, 0, $chars);
		$summary = $summary.'...';
		return $summary;
	}

	/***************
   @@@ Summary @@@
	****************/
	function display_formatted_summary($chars, $text){
		$summary = remove_formatting($text);
		$summary = substr($text, 0, $chars);
		$summary = $summary.'...';
		return $summary;
	}

	/*******************************
		 @@@ display_excerpt @@@
	********************************/
	function display_excerpt($chars, $text){
		$excerpt = substr($text, 0, $chars);
		$excerpt = $excerpt;
		return $excerpt;
	}

	/*******************************
		 @@@ remove_formatting @@@
	********************************/
	function remove_formatting($text){
		$text = preg_replace('/(?:<|&lt;)\/?([a-zA-Z]+) *[^<\/]*?(?:>|&gt;)/', '', $text);
		return $text;
	}

		/*******************************
				@@@ PUBLISH @@@
		********************************/
		function publish($module_caption, $tbl_name, $id){

			$mysqli = $GLOBALS['DB']['MSQLI'];
			$result = $mysqli->query("UPDATE `$tbl_name` SET publish=1 WHERE id='".$id."'");

			if($result)	return true;
			else				return false;
		}

		/*******************************
				@@@ UN-PUBLISH @@@
		********************************/
		function unpublish($module_caption, $tbl_name, $id){

			$mysqli = $GLOBALS['DB']['MSQLI'];
			$result = $mysqli->query("UPDATE `$tbl_name` SET publish=0 WHERE id='".$id."'");

			if($result)	return true;
			else				return false;
		}


		/*******************************
				@@@ _err_ @@@
		********************************/
		function _err_($mysqli){
			if ($mysqli->error) print 'Error : ('. $mysqli->errno .') '. $mysqli->error;
		}

		/*******************************
				 @@@ getTableAttr @@@
		********************************/
		function getTableAttr($field_name, $tbl_name, $id){

			$mysqli = $GLOBALS['DB']['MSQLI'];
			$result = $mysqli->query("SELECT ".$field_name." FROM `".$tbl_name."` WHERE id='$id'");
			$row = $result->fetch_row();
					return stripslashes($row[0]);
		}

	/*******************************
				 @@@ getTableAttr @@@
		********************************/
		function getTableAttrWhere($field_name, $tbl_name,$field_where, $id){


			$mysqli = $GLOBALS['DB']['MSQLI'];
			$result = $mysqli->query("SELECT ".$field_name." FROM `".$tbl_name."` WHERE $field_where='$id'");
			$row = $result->fetch_row();
					return stripslashes($row[0]);
		}







		/*******************************
			 @@@ getTableAttrbySlug @@@
		********************************/
		function getTableAttrbySlug($field_name, $tbl_name, $slug){

      $mysqli = $GLOBALS['DB']['MSQLI'];
      $slug 				= '"'.$mysqli->real_escape_string($slug).'"';
        $result   = $mysqli->query("SELECT " . $field_name . " FROM `" . $tbl_name . "` WHERE slug=$slug");
        $row      = $result->fetch_row();
          $total_rows     = $result->num_rows;
          if ($total_rows>0){
            $return_value = stripslashes($row[0]);
            return $return_value;
          } else {
            return false;
          }
		}

		/*******************************
				 @@@ checkDuplicateRow @@@
		********************************/
		function checkDuplicateRow($tbl_name, $field_name, $field_value){

      $mysqli = $GLOBALS['DB']['MSQLI'];
      // echo "SELECT count(id) FROM `$tbl_name` WHERE ".$field_name."='".$field_value."'";
			$result = $mysqli->query("SELECT count(id) FROM `$tbl_name` WHERE ".$field_name."='".$field_value."'");
			$row = $result->fetch_row();
					return stripslashes($row[0]);
		}


		/*******************************
		 @@@ getPageAttr @@@
		********************************/
		function getPageAttr($field_name, $page_name){

			$mysqli = $GLOBALS['DB']['MSQLI'];
			$result = $mysqli->query("SELECT ".$field_name." FROM mhm_pages WHERE slug='".$page_name."' AND publish=1");
			$row = $result->fetch_array();
					return $row[0];
		}

		/*******************************
				 @@@ getChildCount @@@
		********************************/
		function getChildCount($tbl_name, $field_name, $field_value){

			$mysqli = $GLOBALS['DB']['MSQLI'];
			$result = $mysqli->query("SELECT count(id) FROM `".$tbl_name."` WHERE ".$field_name."='".$field_value."'");
			$row = $result->fetch_row();
					return stripslashes($row[0]);

		}

		/*******************************
				 @@@ timezone @@@
		********************************/
		function timezone($datetime){
			if ( preg_match('/localhost/', $_SERVER['HTTP_HOST']) || preg_match('/127.0.0.1/', $_SERVER['HTTP_HOST']) || preg_match('/192.168.0.105/', $_SERVER['HTTP_HOST']))
				$formatted_datetime =  date("g:i a, j M Y", strtotime("+120 minutes", strtotime(date($datetime))));
			else
				$formatted_datetime =  date("g:i a, j M Y", strtotime("+240 minutes", strtotime(date($datetime))));
			return $formatted_datetime;
		}

		/*******************************
						@@@ fp__ @@@
		********************************/
		function fp__($tbl_name, $id){
			$mysqli = $GLOBALS['DB']['MSQLI'];
			$admin_id	= $_SESSION[$GLOBALS['project_pre']]['admin_id'];
			$mysqli->query("UPDATE `$tbl_name` SET created_by='".$admin_id."', modified='".date("Y-m-d H:i:s")."' WHERE id=$id");
		}


		/*******************************
			@@@ drawPublishedEditDelete @@@
		********************************/
		function drawPublishedEditDelete($module, $id, $publish, $edit, $delete){
			$string = '';

			if ($publish==1) 	$string .= '<a href="listing_'.$module.'.php?action=unpublish_'.$module.'&id='.$id.'" title="Published"><i class="icon-checkmark4"></i></a>&nbsp;';
			if ($edit==1) 		$string .= '<a href="'.$module.'.php?action=edit_'.$module.'&id='.$id.'" title="Edit"><i class=" icon-pencil3"></i></a>&nbsp;';
			if ($delete==1) 	$string .= '<a href="#" data-href="listing_'.$module.'.php?action=delete_'.$module.'&id='.$id.'" data-toggle="modal" data-target="#confirm-delete" title="Delete"><i class="icon-trash"></i></a>';

			return $string;
		}

		/*******************************
		@@@ drawNotPublishedEditDelete @@@
		********************************/
		function drawNotPublishedEditDelete($module, $id, $publish, $edit, $delete){
			$string = '';
      $mysqli = $GLOBALS['DB']['MSQLI'];
      /*$result = $mysqli->query("SELECT * FROM `mt_employees_sims` WHERE sim_id='$id'");
      $row = $result->fetch_row();
      $rcount=$row->count();
     $result = $mysqli->query("SELECT COUNT(*) FROM `mt_employees_sims` WHERE sim_id='$id'");
     $row = $result->fetch_row();
     */
     $result = $mysqli->query("SELECT COUNT(*) FROM `mt_employees_sims` WHERE sim_id='$id'");
     $row = $result->fetch_row();
     $rcount = $row[0];
     //$rcount=0;
     // $simId=$row['sim_id'];
      if($rcount>0){

			if ($publish==1) 	$string .= '<a href="listing_'.$module.'.php?action=publish_'.$module.'&id='.$id.'" title="UnPublished"><i class="icon-cross3"></i></a>&nbsp;';
			if ($edit==1) 		$string .= '<input disabled value="'.$id.'" type="checkbox" name="all_sims" class="all_sims"> <a href="'.$module.'.php?action=edit_'.$module.'&id='.$id.'" title="Edit"><i class=" icon-pencil3"></i></a>&nbsp;';
			if ($delete==1) 	$string .= '<a href="#" data-href="listing_'.$module.'.php?action=delete_'.$module.'&id='.$id.'" data-toggle="modal" data-target="#confirm-delete" title="Delete"><i class="icon-trash"></i></a>';
      } 
      else{
        if ($publish==1)  $string .= '<a href="listing_'.$module.'.php?action=publish_'.$module.'&id='.$id.'" title="UnPublished"><i class="icon-cross3"></i></a>&nbsp;';
      if ($edit==1)     $string .= '<input value="'.$id.'" type="checkbox" name="all_sims" class="all_sims"> <a href="'.$module.'.php?action=edit_'.$module.'&id='.$id.'" title="Edit"><i class=" icon-pencil3"></i></a>&nbsp;';
      if ($delete==1)   $string .= '<a href="#" data-href="listing_'.$module.'.php?action=delete_'.$module.'&id='.$id.'" data-toggle="modal" data-target="#confirm-delete" title="Delete"><i class="icon-trash"></i></a>';

    }  
			return $string;
		}



    /*******************************
        @@@ getEmailsStats @@@
    ********************************/
    function getEmailsStats($status, $admin_id){

      $mysqli = $GLOBALS['DB']['MSQLI'];
      $type = getAdminAttr('type', $admin_id);

      if ($type=='superadmin') 	$created_by = " ";
      else 											$created_by = " AND created_by='$admin_id'";

      switch($status){

        case '':
          $result = $mysqli->query("SELECT count(id) FROM `".$GLOBALS['TBL']['PREFIX']."emails` WHERE id>0 $created_by");
          $row = $result->fetch_array();
          return $row[0];
        break;

        case '1':
          $result = $mysqli->query("SELECT count(id) FROM `".$GLOBALS['TBL']['PREFIX']."emails` WHERE status='1' $created_by");
          $row = $result->fetch_array();
          return $row[0];
        break;

        case '2':
          $result = $mysqli->query("SELECT count(id) FROM `".$GLOBALS['TBL']['PREFIX']."emails` WHERE status='2' $created_by");
          $row = $result->fetch_array();
          return $row[0];
        break;

        default:
          return 0;
      }

    }

    /*******************************
    @@@ getCategoryEmailByStatus @@@
    ********************************/
    function getCategoryEmailByStatus($category, $status, $admin_id){

      $mysqli = $GLOBALS['DB']['MSQLI'];
      $result = $mysqli->query("SELECT count(id) FROM `".$GLOBALS['TBL']['PREFIX']."emails` WHERE category_id='$category' AND status='$status' AND created_by='$admin_id' ");
        $row = $result->fetch_row();
            return $row[0];

    }

    /*******************************
    @@@ getTotalEmailsinCategory @@@
    ********************************/
    function getTotalEmailsinCategory($id){

      $mysqli = $GLOBALS['DB']['MSQLI'];
      $result = $mysqli->query("SELECT count(id) FROM `".$GLOBALS['TBL']['PREFIX']."emails` WHERE category_id=$id AND created_by=".$_SESSION[$project_pre]['admin_id']."");
        $row = $result->fetch_array();
            return $row[0];

    }

    /*******************************
    @@@ getTotalEmailsOfUser @@@
    ********************************/
    function getTotalEmailsOfUser($id){

      $mysqli = $GLOBALS['DB']['MSQLI'];
      $result = $mysqli->query("SELECT count(id) FROM `".$GLOBALS['TBL']['PREFIX']."emails` WHERE created_by=$id");
        $row = $result->fetch_array();
            return $row[0];

    }

    /*************************************
      @@@ checkDuplicateCategory @@@
    **************************************/
    function checkDuplicateCategory($category, $admin_id){

      $mysqli = $GLOBALS['DB']['MSQLI'];
      $result = $mysqli->query("SELECT count(id) FROM `".$GLOBALS['TBL']['PREFIX']."categories` WHERE category='".trim($category)."' AND created_by=$admin_id");
      $row = $result->fetch_array();

      if ($row['0']==0)
        return false;
      else
        return true;
    }

    /*******************************
      @@@ getCategoryNameById @@@
    ********************************/
    function getCategoryNameById($id){

      $mysqli = $GLOBALS['DB']['MSQLI'];
      $result = $mysqli->query("SELECT category FROM `".$GLOBALS['TBL']['PREFIX']."categories` WHERE id=$id");
      $row = $result->fetch_array();
          return $row[0];

    }

    /*******************************
       @@@ getRandCleanedEmail @@@
    ********************************/
    function getRandCleanedEmail(){

      $mysqli = $GLOBALS['DB']['MSQLI'];
      $result = $mysqli->query("SELECT email FROM `".$GLOBALS['TBL']['PREFIX']."emails` WHERE status=1 ORDER BY rand() LIMIT 1");
      $row = $result->fetch_array();
          return $row[0];

    }

    /*******************************
       @@@ getUsernameByID @@@
    ********************************/
    function getUsernameByID($id){

      $mysqli = $GLOBALS['DB']['MSQLI'];
      $result = $mysqli->query("SELECT username FROM `".$GLOBALS['TBL']['PREFIX']."admins` WHERE id='".$id."'");
      $row = $result->fetch_array();
          return $row[0];

    }

    /*******************************
       @@@ getTotalQuota @@@
    ********************************/
    function getTotalQuota($id){

      $mysqli = $GLOBALS['DB']['MSQLI'];
      $result = $mysqli->query("SELECT total_quota FROM `".$GLOBALS['TBL']['PREFIX']."admins` WHERE id='".$id."'");
      $row = $result->fetch_array();
          return $row[0];

    }

    /*******************************
       @@@ getAdminType @@@
    ********************************/
    function getAdminType($id){

      $mysqli = $GLOBALS['DB']['MSQLI'];
      $result = $mysqli->query("SELECT type FROM `".$GLOBALS['TBL']['PREFIX']."admins` WHERE id='".$id."'");
      $row = $result->fetch_array();
          return $row[0];

    }

    /*******************************
       @@@ getUsedQuota @@@
    ********************************/
    function getUsedQuota($id){

      $mysqli = $GLOBALS['DB']['MSQLI'];
      $result = $mysqli->query("SELECT used_quota FROM `".$GLOBALS['TBL']['PREFIX']."admins` WHERE id='".$id."'");
      $row = $result->fetch_array();
          return $row[0];

    }

    /*******************************
       @@@ updateUsedQuota @@@
    ********************************/
    function updateUsedQuota($id){

      $mysqli = $GLOBALS['DB']['MSQLI'];
      $used_quota = getUsedQuota($id); $used_quota++;
      $mysqli->query("UPDATE `".$GLOBALS['TBL']['PREFIX']."admins` SET used_quota='$used_quota' WHERE id='".$id."'");

    }

    /*******************************
         @@@ getAdminAttr @@@
    ********************************/
    function getAdminAttr($field_name, $id){

      $mysqli = $GLOBALS['DB']['MSQLI'];
      $result = $mysqli->query("SELECT ".$field_name." FROM `".$GLOBALS['TBL']['PREFIX']."admins` WHERE id='$id'");
      $row = $result->fetch_row();
          return stripslashes($row[0]);

    }




    /*******************************
      @@@ getCreatedBy_EmailID @@@
    ********************************/
    function getCreatedBy_EmailID($id){

      $mysqli = $GLOBALS['DB']['MSQLI'];
      $result = $mysqli->query("SELECT created_by FROM `".$GLOBALS['TBL']['PREFIX']."emails` WHERE id=$id");
      $row = $result->fetch_array();
          return $row[0];
    }

    /*******************************
       @@@ updateEmailStats @@@
    ********************************/
    function updateEmailStats($status, $id){

      $mysqli = $GLOBALS['DB']['MSQLI'];

      if ($status==1){

        $total_cleaned_emails = getEmailsStats(1, getCreatedBy_EmailID($id));

        $result = $mysqli->query("SELECT total_quota FROM `".$GLOBALS['TBL']['PREFIX']."admins` WHERE id='".$id."'");
        $row = $result->fetch_array();
            return $row[0];

      } else if ($status==2){
      }

    }


    /*******************************
          @@@ getMyAvatar @@@
    ********************************/
    function getMyAvatar($id){

      $mysqli = $GLOBALS['DB']['MSQLI'];
      $result = $mysqli->query("SELECT photo FROM `".$GLOBALS['TBL']['PREFIX']."admins` WHERE id='$id'");
      $row = $result->fetch_array();
          return $row[0];
    }

    /*******************************
          @@@ getCount @@@
    ********************************/
    function getCount($table_name, $condition=''){

      $mysqli = $GLOBALS['DB']['MSQLI'];
      if (!empty($condition))
        $result = $mysqli->query("SELECT count(id) FROM $table_name WHERE ".$condition."");
      else
        $result = $mysqli->query("SELECT count(id) FROM $table_name");
        $row = $result->fetch_array();
            return $row[0];

    }

    /*******************************
        @@@ getBackground @@@
    ********************************/
    function getBackground($slug){

      $mysqli = $GLOBALS['DB']['MSQLI'];
      $result = $mysqli->query("SELECT photo FROM `".$GLOBALS['TBL']['PREFIX']."backgrounds` WHERE slug='".$slug."' AND publish=1");
      $row = $result->fetch_array();
          return $row[0];

    }

    /*******************************
       @@@ getPageNameBySlug @@@
    ********************************/
    function getPageNameBySlug($slug){

      $mysqli = $GLOBALS['DB']['MSQLI'];
      $slug 				= '"'.$mysqli->real_escape_string($slug).'"';
      $result = $mysqli->query("SELECT page_name FROM `".$GLOBALS['TBL']['PREFIX']."slugs` WHERE slug=$slug"); _err_($mysqli);
      $row = $result->fetch_array();
        return stripslashes($row[0]);
    }

    /*******************************
        @@@ checkSocial @@@
    ********************************/
    function checkSocial($slug){

      $mysqli = $GLOBALS['DB']['MSQLI'];
      $result = $mysqli->query("SELECT publish FROM `".$GLOBALS['TBL']['PREFIX']."socials` WHERE slug='".$slug."'");
      $row = $result->fetch_array();
          return $row[0];
    }

    /*******************************
        @@@ getSocialURL @@@
    ********************************/
    function getSocialURL($slug){

      $mysqli = $GLOBALS['DB']['MSQLI'];
      $result = $mysqli->query("SELECT url FROM `".$GLOBALS['TBL']['PREFIX']."socials` WHERE slug='".$slug."'");
      $row = $result->fetch_array();
          return $row[0];
    }

    /*******************************
        @@@ getPageHeading @@@
    ********************************/
    function getPageHeading($slug){

      $mysqli = $GLOBALS['DB']['MSQLI'];
      $result = $mysqli->query("SELECT heading FROM `".$GLOBALS['TBL']['PREFIX']."pages` WHERE slug='".$slug."'");
      $row = $result->fetch_array();
          return $row[0];
    }

    /*******************************
        @@@ getPageSubheading @@@
    ********************************/
    function getPageSubheading($slug){

      $mysqli = $GLOBALS['DB']['MSQLI'];
      $result = $mysqli->query("SELECT subheading FROM `".$GLOBALS['TBL']['PREFIX']."captions` WHERE slug='".$slug."'");
      $row = $result->fetch_array();
          return $row[0];
    }

    /*******************************
       @@@ getIDFromUsername @@@
    ********************************/
    function getIDFromUsername($username, $table_name){

      $mysqli = $GLOBALS['DB']['MSQLI'];
      $result = $mysqli->query("SELECT id FROM `".$table_name."` WHERE username= '".$username."' ");
      $row = $result->fetch_array();
          return $row[0];
    }

    /*******************************
       @@@ getUsernameFromID @@@
    ********************************/
    function getUsernameFromID($id, $table_name){

      $mysqli = $GLOBALS['DB']['MSQLI'];
      $result = $mysqli->query("SELECT username FROM `".$table_name."` WHERE id='".$id."' ");
      $row = $result->fetch_array();
          return $row[0];
    }

    /*************************************
      @@@ checkDuplicateUsername @@@
    **************************************/
    function checkDuplicateUsername($username, $table_name){

      $mysqli = $GLOBALS['DB']['MSQLI'];
      $result = $mysqli->query("SELECT username FROM `".$GLOBALS['TBL']['PREFIX']."admins` WHERE username='$username'");
      $row = $result->fetch_array();
          if (!empty($row['0'])) return true;
          else return false;
    }

    /*************************************
      @@@ checkDuplicateEmail @@@
    **************************************/
    function checkDuplicateEmail($email, $id='', $table_name){

      $mysqli = $GLOBALS['DB']['MSQLI'];

      if (!empty($id)){
        $result = $mysqli->query("SELECT email FROM `".$GLOBALS['TBL']['PREFIX']."admins` WHERE email='$email' AND id!='$id'");
        $row = $result->fetch_array();
      } else {
        $result = $mysqli->query("SELECT email FROM `".$GLOBALS['TBL']['PREFIX']."admins` WHERE email='$email'");
        $row = $result->fetch_array();
      }

          if (!empty($row['0'])) return true;
          else return false;
    }


    /*************************************
      @@@ COMPLETE DATABASE BACKUP  @@@
    **************************************/
    /* backup the db OR just a table */
    ///function backup_tables($host, $user, $pass, $name, $tables = '*')
    function backup_tables($mysqli, $tables = '*')
    {
      $mysqli = $mysqli;
      //get all of the tables
      if($tables == '*')
      {
        $tables = array();
        $result = $mysqli->query("SHOW TABLES"); //_err_($mysqli);
        while($row = $result->fetch_array())
        {
          $tables[] = $row[0];
        }
      }
      else
      {
        $tables = is_array($tables) ? $tables : explode(',',$tables);
      }

      $return = '';
      //cycle through
      foreach($tables as $table)
      {
        $result = $mysqli->query("SELECT * FROM ".$table);
        $num_fields = mysqli_num_fields($result);

        $return.= 'DROP TABLE IF EXISTS `'.$table.'`;';
        $row2 = mysqli_fetch_row($mysqli->query("SHOW CREATE TABLE ".$table));
        $return.= "\n\n".$row2[1].";\n\n";

        for ($i = 0; $i < $num_fields; $i++)
        {
          while($row = $result->fetch_array())
          {
            $return.= 'INSERT INTO '.$table.' VALUES(';
            for($j=0; $j<$num_fields; $j++)
            {
              $row[$j] = addslashes($row[$j]);
              $row[$j] = str_replace("\n", "\\n", $row[$j]);

              if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
              if ($j<($num_fields-1)) { $return.= ','; }
            }
            $return.= ");\n";
          }
        }
        $return.="\n\n\n";
      }

      //save file
      //$handle = fopen('db/db-'.time().'-'.(md5(implode(',',$tables))).'.sql','w+');
      $handle = fopen('cron/'.date("Y-m-d_His").'.sql','w+');
      fwrite($handle,$return);
      fclose($handle);
    }

    /*******************************
    @@@ getNextGalleryImage @@@
    ********************************/
    function getNextGalleryImage($album_id){

      $mysqli = $GLOBALS['DB']['MSQLI'];
      $result123 = $mysqli->query("SELECT photo FROM `".$GLOBALS['TBL']['PREFIX']."galleries` WHERE category_id='".$album_id."' AND publish=1 ORDER BY ordering LIMIT 1");
      $row123 = $result123->fetch_array();
          return $row123['photo'];
    }


		/*************************************
			@@@ Time to AGO @@@
		**************************************/
		function time_elapsed_string($datetime, $full = false) {
		    $now = new DateTime;
		    $ago = new DateTime($datetime);
		    $diff = $now->diff($ago);

		    $diff->w = floor($diff->d / 7);
		    $diff->d -= $diff->w * 7;

		    $string = array(
		        'y' => 'year',
		        'm' => 'month',
		        'w' => 'week',
		        'd' => 'day',
		        'h' => 'hour',
		        'i' => 'minute',
		        's' => 'second',
		    );
		    foreach ($string as $k => &$v) {
		        if ($diff->$k) {
		            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
		        } else {
		            unset($string[$k]);
		        }
		    }

		    if (!$full) $string = array_slice($string, 0, 1);
		    return $string ? implode(', ', $string) . ' ago' : 'just now';
		}


  /*************************************
  @@@ processTelephone @@@
  **************************************/
  function h_tel($telephone){

    $tel = $telephone;
    if (preg_match('/,_/', $telephone)){
      $tel     				= str_ireplace(',_', ', ', $telephone);
    }

    return $tel;
  }

  /*************************************
  @@@ Dial Tel @@@
  **************************************/
  function dial_tel($telephone){

    $dial								= '';

    if (preg_match('/,/', $telephone)){
      $tel_array    = explode(',', $telephone);
      $tel          = $tel_array[0];
      $tel          = str_ireplace(' ', '', $telephone);
      $tel          = str_ireplace('+', '00', $tel);
      $tel          = str_ireplace('-', '', $tel);

    } else {
      $tel          = $telephone;
      $tel          = str_ireplace(' ', '', $telephone);
      $tel          = str_ireplace('+', '00', $tel);
      $tel          = str_ireplace('-', '', $tel);
    }

    return $tel;
  }

  /*************************************
  @@@ show_company_rating @@@
  **************************************/
  function show_company_rating($slug){

    $mysqli = $GLOBALS['DB']['MSQLI'];
    $result = $mysqli->query("SELECT views, amp_views FROM `".$GLOBALS['TBL']['PREFIX']."companies` WHERE slug='".$slug."' LIMIT 1");
    $row    = $result->fetch_array();
      $views      = $row['views'];
      // $amp_views  = $row['amp_views'];
      if ($views==0){
        $digits = 0;
      } else {

        // $digits    = strlen($views/5);
        $digits    = $views/5;

        if (is_float($digits)){
          $float  = explode('.', $digits);
          $digits = strlen($views/5).'.'.$float['1'];

        } else {
          $digits    = strlen($views/5);
        }
      }

    return $digits;
  }

  /*************************************
  @@@ h_website @@@
  **************************************/
  function h_website($website){
    // *** ADD www. http ***
    if (!empty($website)){

      if ( !preg_match('/www./', $website) )
      $website = 'www.'. strtolower($website);

      if ( !preg_match('/http/', $website) && !preg_match('/https/', $website) )
      $website = 'http://'.  strtolower($website);

      // if ( !preg_match('/www./', $website) && !preg_match('/http/', $website) && !preg_match('/https/', $website) )
      // $website = 'http://www.'.str_replace('www.', '', strtolower($website) );
    }
    $website = str_ireplace(' ', '', $website);
    return $website;
  }

  /*************************************
  @@@ processExternalwebsite @@@
  **************************************/
  function processExternalwebsite($website){
    // *** ADD www. http ***
    if (!empty($website)){

      if ( !preg_match('/www./', $website) )
      $website = 'www.'. strtolower($website);

      if ( !preg_match('/http/', $website) && !preg_match('/https/', $website) )
      $website = 'http://'.  strtolower($website);

      // if ( !preg_match('/www./', $website) && !preg_match('/http/', $website) && !preg_match('/https/', $website) )
      // $website = 'http://www.'.str_replace('www.', '', strtolower($website) );
    }
    $website = str_ireplace(' ', '', $website);
    return $website;
  }


/*************************************
  @@@ GENERATE SITEMAP @@@
 **************************************/
function generate_sitemap($sitemap, $type = 'default', $source_id = 0)
{

  if (empty($sitemap))
    die('Please select sitemap to proceed.');


  $sitemap_path     = '../gse';

  $mysqli           = $GLOBALS['DB']['MSQLI'];
  $base_url         = $GLOBALS['SETTINGS']['SITE_URL'];

  $module           = str_ireplace('.xml', '', $sitemap); // without .xml

  // HTTP
  if ($type == 'default') { // DO NOTHING - DEFAULT IS HTTP
    $base_url         = $base_url;
    $file             = $sitemap;

    // SSL
  } else if ($type == 'ssl') {
    $base_url         = str_ireplace('http://www.', 'https://www.', $base_url);
    $file               = str_ireplace('.xml', '_ssl.xml', $sitemap);          // SSL  sitemap


    // AMP
  } else if ($type == 'amp') {
    $base_url         .= '/amp';
    $file            = str_ireplace('.xml', '_amp.xml', $sitemap);        // AMP  sitemap

    // AMP SSL
  } else if ($type == 'amp_s') {
    $base_url         = str_ireplace('http://www.', 'https://www.', $base_url);
    $base_url        .= '/amp';
    $file             = str_ireplace('.xml', '_amp_s.xml', $sitemap);      // AMP  SSL sitemap
  }


  //************************
  if ($module == 'articles') {
    //************************

    $xml = '<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

    $result     = $mysqli->query("SELECT id, slug FROM `" . $GLOBALS['TBL']['PREFIX'] . "$module` WHERE publish=1 ORDER BY id DESC");
    while ($row  = $result->fetch_array()) {
      $id     = $row['id'];
      $slug   = $row['slug'];

      if (!empty($slug)) {
        $xml .= '
<url>
<loc>' . $base_url . '/article/' . $slug . '</loc>
</url>';
      } //endif

    } //while
    $xml .= '
</urlset>';

    // WRITE SITEMAP
    $done_sitemap = fopen($sitemap_path . '/' . $file, "w") or die("Unable to open file!");
    fwrite($done_sitemap, trim($xml));
    fclose($done_sitemap);


    //****************************************
  } else if ($module == 'article_categories') {
    //****************************************

    $xml = '<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

    $result     = $mysqli->query("SELECT id, slug FROM `" . $GLOBALS['TBL']['PREFIX'] . "$module` WHERE publish=1 ORDER BY id DESC");
    while ($row  = $result->fetch_array()) {
      $id     = $row['id'];
      $slug   = $row['slug'];

      if (!empty($slug)) {
        $xml .= '
<url>
<loc>' . $base_url . '/articles/' . $slug . '</loc>
</url>';
      } //endif

    } //while
    $xml .= '
</urlset>';

    // WRITE SITEMAP
    $done_sitemap = fopen($sitemap_path . '/' . $file, "w") or die("Unable to open file!");
    fwrite($done_sitemap, trim($xml));
    fclose($done_sitemap);

    //**********************************
    } else  if ($module == 'services') {
    //**********************************

      $xml = '<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

      $result     = $mysqli->query("SELECT id, slug FROM `" . $GLOBALS['TBL']['PREFIX'] . "$module` WHERE publish=1 ORDER BY id DESC");
      while ($row  = $result->fetch_array()) {
        $id     = $row['id'];
        $slug   = $row['slug'];

        if (!empty($slug)) {
          $xml .= '
<url>
<loc>' . $base_url . '/service/' . $slug . '</loc>
</url>';
        } //endif

      } //while
      $xml .= '
</urlset>';

      // WRITE SITEMAP
      $done_sitemap = fopen($sitemap_path . '/' . $file, "w") or die("Unable to open file!");
      fwrite($done_sitemap, trim($xml));
      fclose($done_sitemap);


    //*********************************
    } else if ($module == 'projects') {
    //*********************************

      $xml = '<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

      $result     = $mysqli->query("SELECT id, slug FROM `" . $GLOBALS['TBL']['PREFIX'] . "$module` WHERE publish=1 ORDER BY id DESC");
      while ($row  = $result->fetch_array()) {
        $id     = $row['id'];
        $slug   = $row['slug'];

        if (!empty($slug)) {
          $xml .= '
<url>
<loc>' . $base_url . '/project/' . $slug . '</loc>
</url>';
        } //endif

      } //while
      $xml .= '
</urlset>';

      // WRITE SITEMAP
      $done_sitemap = fopen($sitemap_path . '/' . $file, "w") or die("Unable to open file!");
      fwrite($done_sitemap, trim($xml));
      fclose($done_sitemap);


      //****************************************
    } else if ($module == 'project_categories') {
      //****************************************

      $xml = '<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

      $result     = $mysqli->query("SELECT id, slug FROM `" . $GLOBALS['TBL']['PREFIX'] . "$module` WHERE publish=1 ORDER BY id DESC");
      while ($row  = $result->fetch_array()) {
        $id     = $row['id'];
        $slug   = $row['slug'];

        if (!empty($slug)) {
          $xml .= '
<url>
<loc>' . $base_url . '/projects/' . $slug . '</loc>
</url>';
        } //endif

      } //while
      $xml .= '
</urlset>';

      // WRITE SITEMAP
      $done_sitemap = fopen($sitemap_path . '/' . $file, "w") or die("Unable to open file!");
      fwrite($done_sitemap, trim($xml));
      fclose($done_sitemap);

    //****************************************
  } else if ($module == 'pages') {
    //****************************************

    $xml = '<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
    $xml .= '<url><loc>' . $base_url . '/</loc></url>'; //homepage

    $result     = $mysqli->query("SELECT id, slug, php_page FROM `" . $GLOBALS['TBL']['PREFIX'] . "pages` WHERE slug!='home'");
    while ($row  = $result->fetch_array()) {
      $id       = $row['id'];
      $slug     = $row['slug'];
      $php_page = $row['php_page'];

      if ($php_page == 1) $page = '';
      else              $page = 'page/';

      if (!empty($slug)) {
        $xml .= '
<url>
<loc>' . $base_url . '/' . $page . $slug . '</loc>
</url>';
      } //endif

    } //while
    $xml .= '
</urlset>';

    // WRITE SITEMAP
    $done_sitemap = fopen($sitemap_path . '/' . $file, "w") or die("Unable to open file!");
    fwrite($done_sitemap, trim($xml));
    fclose($done_sitemap);
 
    
    }// endif


} // function



/******************************
@@@ Show Article Categories @@@
*******************************/
function show_article_categories($category_id)
{
  $menus = '';
  $menus .= '<label class="radio-inline"><div class="choice"><input type="radio" name="radio-inline-left" class="styled"></div>Parent</label><br /><br />';
  $menus .= generate_multilevel_article_categories($parent_id = 0, $category_id);
  return $menus;
}

/**********************************************
@@@ GENERATE MULTI-LEVEL ARTICLE CATEGORIES @@@
***********************************************/
function generate_multilevel_article_categories($parent_id = 0, $category_id)
{

  $mysqli = $GLOBALS['DB']['MSQLI'];
  $menu     = "";
  $sql       = "";
  $checked   = "";

  if (is_null($parent_id)) {
    $result = $mysqli->query("SELECT * FROM `" . $GLOBALS['TBL']['PREFIX'] . "article_categories` WHERE parent_id=0 ORDER BY category");
  } else {
    $result = $mysqli->query("SELECT * FROM `" . $GLOBALS['TBL']['PREFIX'] . "article_categories` WHERE parent_id=$parent_id ORDER BY category");
  }

  while ($row = $result->fetch_assoc()) {
    $id             = $row['id'];
    $slug           = $row['slug'];
    $category_name  = $row['category'];
    $publish        = $row['publish'];

    if ($id) {

      if ($id == $category_id)  $category_name = '<strong>' . $category_name . '</strong>';
      else                      $category_name = $category_name;

      // $menu .= '<label class="radio-inline"><div class="choice"><input type="radio" name="parent_id" id="parent_id" value="'.$id.'" class="styled"></div>'.$category_name.'</label>&nbsp;&nbsp;&nbsp;&nbsp;<a href="media_categories.php?action=edit_media_categories&id='.$id.'&outlet='.$outlet.'" title="Edit"><i class=" icon-pencil3"></i></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="upload_media.php?action=upload_media&category_id='.$id.'&outlet='.$outlet.'" title="Edit">Add Media</a>';

      // $menu .= '<label class="radio-inline"><div class="choice"><input type="radio" name="parent_id" id="parent_id" value="' . $id . '" class="styled"></div><a href="#"><i class="icon-folder2"></i></a>&nbsp;&nbsp;&nbsp;' . $category_name . '</label>&nbsp;&nbsp;<a href="upload_media.php?action=upload_media&category_id=' . $id . '"><i class="icon-folder-upload"></i></a>';

        $total_articles   = $mysqli->query("SELECT id FROM `" . $GLOBALS['TBL']['PREFIX'] . "articles` WHERE category=$id");
        $total_articles   = number_format($total_articles->num_rows);

        $delete_icon = '';      
        // SHOW DELETE BUTTON
        
        if ($total_articles < 1){
          $delete_icon = '<a href="?action=delete_article_categories&id=' . $id . '"><i class="icon-trash"></i></a>';               
        }

        $disabled = '';

        // DISABLE NOT-PUBLISHED RADIO
        if ($publish == 0){
          $disabled  = 'disabled = "disabled"';
        }

          $menu .= '<label class="radio-inline"><div class="choice"><input type="radio" name="parent_id" id="parent_id" value="' . $id . '" class="styled" '.$disabled.'></div>' . $category_name . '</label>&nbsp;&nbsp;<a href="?action=edit_article_categories&id=' . $id . '"><i class="icon-pencil3"></i></a>  &nbsp;'. $delete_icon.'&nbsp;&nbsp;'. number_format($total_articles);
      
    }

    $menu .= '<ul class="dropdown">' . generate_multilevel_article_categories($id, $category_id) . '</ul>';
    $menu .= '</li>';
  } //while

  return $menu;
}


/***************************************
@@@ Show Frontend Article Categories @@@
 ***************************************/
function show_farticle_categories($category_id)
{
  $menus = '';
  $menus .= generate_multilevel_farticle_categories($parent_id = 0, $category_id);
  return $menus;
}

/**********************************************
@@@ GENERATE MULTI-LEVEL ARTICLE CATEGORIES @@@
 ***********************************************/
function generate_multilevel_farticle_categories($parent_id = 0, $category_id)
{

  $base_url = $GLOBALS['SETTINGS']['BASE_URL'];
  $mysqli   = $GLOBALS['DB']['MSQLI'];
  $menu     = "";
  $sql      = "";
  $checked  = "";

  if (is_null($parent_id)) {
    $result = $mysqli->query("SELECT * FROM `" . $GLOBALS['TBL']['PREFIX'] . "article_categories` WHERE parent_id=0 ORDER BY category");
  } else {
    $result = $mysqli->query("SELECT * FROM `" . $GLOBALS['TBL']['PREFIX'] . "article_categories` WHERE parent_id=$parent_id ORDER BY category");
  }

  while ($row = $result->fetch_assoc()) {
    $id             = $row['id'];
    $slug           = $row['slug'];
    $category_name  = $row['category'];

    if ($id) {
      // $total_articles   = $mysqli->query("SELECT id FROM `" . $GLOBALS['TBL']['PREFIX'] . "articles` WHERE category=$id");
      // $total_articles   = number_format($total_articles->num_rows);
      
      $menu .= '<li><a href="'. $base_url.'/articles/'.$slug.'">' . $category_name . '</a></li>';
    }

    $menu .= '<ul>' . generate_multilevel_farticle_categories($id, $category_id) . '</ul>';
    $menu .= '</li>';
  } //while

  return $menu;
}