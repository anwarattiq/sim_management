<style>
<?php
  // require_once ('config/globals.php');// MINIFY CSS FILES
  // include_once ('minify.php');

  if (isRemote()){
    include('minified_live.php'); // LIVE MINIFIED FILES

  } else {
    include('minified_local.php'); // LOCAL MINIFIED FILES
  }
?>
</style>
