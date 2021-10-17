<?php
	if (isRemote()){
		$GLOBALS['DB']['HOSTNAME'] = 'localhost';
		$GLOBALS['DB']['DATABASE'] = 'sfgtys_sim';
		$GLOBALS['DB']['USERNAME'] = 'sfgtys_sim_root';
		$GLOBALS['DB']['PASSWORD'] = '10stor78QW@';

	} else {
		$GLOBALS['DB']['HOSTNAME'] = 'localhost';
		$GLOBALS['DB']['DATABASE'] = 'sim';
		$GLOBALS['DB']['USERNAME'] = 'root';
		$GLOBALS['DB']['PASSWORD'] = '';
	}

	$db_name     = $GLOBALS['DB']['DATABASE'];
	$db_host     = $GLOBALS['DB']['HOSTNAME'];
	$db_username = $GLOBALS['DB']['USERNAME'];
	$db_password = $GLOBALS['DB']['PASSWORD'];

	$mysqli = new mysqli($db_host, $db_username, $db_password, $db_name);

	if ($mysqli->connect_error)
		die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);

	//SET TO utf8 Encoding
	$mysqli->set_charset("utf8");

	$GLOBALS['DB']['MSQLI']      = $mysqli;
	$tbl_prefix                  = 'mt_';
	$GLOBALS['TBL']['PREFIX']    = $tbl_prefix;
	$project_pre                 = 'MT'; 				// Using througout the Project for Unique Project Sessions
	$GLOBALS['project_pre']      = $project_pre;    // fp__($tbl_name, $id) globals.php


	//=========================================
	// add WWW. TO http:// + https
	//=========================================
	// if ( isRemote() ){
	// 	if ( !preg_match ('/www./', $_SERVER['HTTP_HOST']) || empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "off" ) {
	// 		header('Location:https://www.' . str_replace('www.', '', $_SERVER['HTTP_HOST']) . $_SERVER['REQUEST_URI']);
	// 		exit();
	// 	}
	// // local move to ssl
	// } else {
	// 	if ( empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "off" ) {
	// 		header('Location:https://'.  $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
	// 		exit();
	// 	}
	// }


	//===================================================================================================
	// SETTINGS TBL
	//===================================================================================================
	$result_settings = $mysqli->query("SELECT * FROM `".$GLOBALS['TBL']['PREFIX']."settings` WHERE id=1");
	$row_settings		 = $result_settings->fetch_array();

	$GLOBALS['SETTINGS']['MINIFY_CSS']									= stripslashes($row_settings['minify_css']);
	$GLOBALS['SETTINGS']['MINIFY_JS']									= stripslashes($row_settings['minify_js']);

	$GLOBALS['SETTINGS']['AMP_LOGO']						      		= stripslashes($row_settings['amp_logo']);
	$GLOBALS['SETTINGS']['LOGO']						      			= stripslashes($row_settings['logo']);
	$GLOBALS['SETTINGS']['LOGO_FOOTER']						     		= stripslashes($row_settings['logo_footer']);
	$GLOBALS['SETTINGS']['FAVICONS']						     		= stripslashes($row_settings['favicons']);
	$GLOBALS['SETTINGS']['SITE_TITLE']									= stripslashes($row_settings['site_title']);
	$GLOBALS['SETTINGS']['SITE_URL']						  			= stripslashes($row_settings['site_url']);
	$GLOBALS['SETTINGS']['META_TITLE']									= stripslashes($row_settings['meta_title']);
	$GLOBALS['SETTINGS']['META_DESCRIPTION']							= stripslashes($row_settings['meta_description']);

	$GLOBALS['SETTINGS']['WHATSAPP']									= stripslashes($row_settings['whatsapp']);
	$GLOBALS['SETTINGS']['FOOTER_SUMMARY']								= stripslashes($row_settings['footer_summary']);
	$GLOBALS['SETTINGS']['CONTACT_NUMBER']								= stripslashes($row_settings['contact_number']);
	
	$GLOBALS['SETTINGS']['EMAIL']										= stripslashes($row_settings['email']);
	$GLOBALS['SETTINGS']['ADDRESS']										= stripslashes($row_settings['address']);

	$GLOBALS['SETTINGS']['LISTING_LIMIT']								= stripslashes($row_settings['listing_limit']);
	$GLOBALS['SETTINGS']['SITEMAP_ROOT']								= stripslashes($row_settings['sitemap_root']);
	// $GLOBALS['SETTINGS']['SITEMAP_PAGES']								= stripslashes($row_settings['sitemap_pages']);
	// $GLOBALS['SETTINGS']['SITEMAP_ARTICLES']							= stripslashes($row_settings['sitemap_articles']);
	// $GLOBALS['SETTINGS']['SITEMAP_ARTICLE_CATEGORIES']					= stripslashes($row_settings['sitemap_article_categories']);

	$GLOBALS['SETTINGS']['GMB']											= stripslashes($row_settings['gmb']);
	$GLOBALS['SETTINGS']['FACEBOOK']									= stripslashes($row_settings['facebook']);
	$GLOBALS['SETTINGS']['TWITTER']										= stripslashes($row_settings['twitter']);
	$GLOBALS['SETTINGS']['PINTEREST']									= stripslashes($row_settings['pinterest']);
	$GLOBALS['SETTINGS']['LINKEDIN']									= stripslashes($row_settings['linkedin']);
	$GLOBALS['SETTINGS']['MEDIUM']										= stripslashes($row_settings['medium']);
	$GLOBALS['SETTINGS']['QUORA']										= stripslashes($row_settings['quora']);
	$GLOBALS['SETTINGS']['INSTAGRAM']									= stripslashes($row_settings['instagram']);
	$GLOBALS['SETTINGS']['KOBO']										= stripslashes($row_settings['kobo']);
