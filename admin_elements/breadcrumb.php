<div class="page-header page-header-default">

  <?php if (!empty($_REQUEST['error_message'])){ ?>
      <div class="alert alert-danger no-border">
        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
        <span class="text-semibold"><?php echo $_REQUEST['error_message'];?><span>
      </div>
  <?php } else if (!empty($error_message)){ ?>
      <div class="alert alert-danger no-border">
        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
        <span class="text-semibold"><?php echo $error_message;?><span>
      </div>
  <?php }?>


  <?php if (!empty($_REQUEST['success_message'])){ ?>
      <div class="alert alert-success no-border">
        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
        <span class="text-semibold"><?php echo $_REQUEST['success_message'];?></span>
      </div>
  <?php } else if (!empty($success_message)){ ?>
      <div class="alert alert-success no-border">
  			<button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
  			<span class="text-semibold"><?php echo $success_message;?></span>
  	  </div>
  <?php }?>

</div>
