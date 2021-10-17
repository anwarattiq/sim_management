<?php

  // https://github.com/matthiasmullie/minify
  // LIVE at https://www.minifier.org

  require_once '../libs/minify/src/Minify.php';
  require_once '../libs/minify/src/JS.php';
  // require_once 'minify/src/CSS.php'; // DO NOT USE CSS is not working well
  require_once '../libs/minify/src/Exception.php';

  use MatthiasMullie\Minify;
  // $cssMin = new Minify\CSS('css/style.css');

  $sourcePath = 'jquery.min.js';
  $minifier = new Minify\JS($sourcePath);

  // we can even add another file, they'll then be
  // joined in 1 output file
  // $sourcePath2 = 'ajax.js';

  $minifier->add('jquery.min.js');
  $minifier->add('popper.min.js');
  $minifier->add('bootstrap.min.js');
  $minifier->add('jquery.meanmenu.js');
  $minifier->add('jquery.appear.min.js');
  $minifier->add('odometer.min.js');
  $minifier->add('owl.carousel.min.js');
  $minifier->add('jquery.magnific-popup.min.js');
  $minifier->add('jquery.nice-select.min.js');
  $minifier->add('jquery.ajaxchimp.min.js');
  $minifier->add('form-validator.min.js');
  $minifier->add('contact-form-script.js');
  $minifier->add('wow.min.js');
  $minifier->add('main.js');
  $minifier->add('ajax.js');
  $minifier->add('site.js');

  // save minified file to disk
  $minifiedPath = 'minified.php';
  $minifier->minify($minifiedPath);

  echo 'JS files have been minified.';
  // $success_message .= '<br />JS files have been minified.';
