<?php
  //===================
  // BURL PROCESSING
  //===================
  $current_page_url = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
  $path             = parse_url($current_page_url, PHP_URL_PATH);
  $url_params       = explode("/", trim($path, "/")); // trim to prevent empty array elements


  if (!isRemote() || preg_match('/thewebai.com/i', $base_url) ){
    //NO NEED for array_shift in case of LIVE
    array_shift($url_params); // GET ONLY PARAMS 127.0.0.1/haiuae/params NOT site like 127.0.0.1/haiuae/
  }


  // POPULATE SLUG
  $slugs = array();
  foreach ($url_params as $url_param) {

    if (!empty($url_param)) {
      array_push($slugs, $mysqli->real_escape_string(stripslashes($url_param)) );
    }

  } // foreach

  $slug1 = '';
  $slug2 = '';
  $slug3 = '';
  $slug4 = '';

  if ( isset($slugs[0]) && !empty($slugs[0]) ) $slug1 = $slugs[0];
  if ( isset($slugs[1]) && !empty($slugs[1]) ) $slug2 = $slugs[1];
  if ( isset($slugs[2]) && !empty($slugs[2]) ) $slug3 = $slugs[2];
  if ( isset($slugs[3]) && !empty($slugs[3]) ) $slug4 = $slugs[3];
