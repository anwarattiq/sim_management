<!-- hight search in dataTables-->
<!-- <script src="js/dataTables.searchHighlight.min.js"></script>
  <script src="js/jquery.highlight.js"></script> -->

<?php if ($current_page == 'aboutme.php' || $current_page == 'pages.php' || $current_page == 'articles.php' || $current_page == 'projects.php' || $current_page == 'services.php' || $current_page == 'service_tabs.php' || $current_page == 'companies_verified.php' || $current_page == 'companies.php' ||  $current_page == 'contact.php' ||  $current_page == 'email_templates.php' ||  $current_page == 'email_template.php' ||  $current_page == 'send_email.php' ||  $current_page == 'email_campaigns.php' || $current_page == 'content.php') { ?>
  <!-- /********* TINY MCE *********/ -->
  <script src="<?php echo $admin_base_url; ?>/js/tinymce/tinymce.min.js"></script>
  <script>
    tinymce.init({

      selector: "textarea#description, textarea#page_description, textarea#service_description, textarea#service_tab_description, textarea#email_template, textarea#article_description, textarea#project_description, textarea#email_body",

      <?php if ($current_page == 'email_templates.php') { ?>
        // width: 1550,
        // height: 700,
        force_br_newlines: false,
        force_p_newlines: false,
        forced_root_block: '',
      <?php } else { ?>
        height: 700,
      <?php } ?>

      plugins: 'code <?php if ($current_page == 'email_templates.php') { ?>fullpage, <?php } //enables html headers to save in html templates
                                                                                      ?> print preview fullpage searchreplace autolink directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern help',

      toolbar: 'code formatselect | bold italic strikethrough forecolor backcolor | link image media | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent | removeformat | addcomment',
      automatic_uploads: true,
      /*
         URL of our upload handler (for more details check: https://www.tiny.cloud/docs/configure/file-image-upload/#images_upload_url)
         images_upload_url: 'postAcceptor.php',
         here we add custom filepicker only to Image dialog
       */
      file_picker_types: 'image',
      /* and here's our custom image picker*/
      file_picker_callback: function(cb, value, meta) {
        var input = document.createElement('input');
        input.setAttribute('type', 'file');
        input.setAttribute('accept', 'image/*');

        /*
          Note: In modern browsers input[type="file"] is functional without
          even adding it to the DOM, but that might not be the case in some older
          or quirky browsers like IE, so you might want to add it to the DOM
          just in case, and visually hide it. And do not forget do remove it
          once you do not need it anymore.
        */

        input.onchange = function() {
          var file = this.files[0];

          var reader = new FileReader();
          reader.onload = function() {
            /*
              Note: Now we need to register the blob in TinyMCEs image blob
              registry. In the next release this part hopefully won't be
              necessary, as we are looking to handle it internally.
            */
            var id = 'blobid' + (new Date()).getTime();
            var blobCache = tinymce.activeEditor.editorUpload.blobCache;
            var base64 = reader.result.split(',')[1];
            var blobInfo = blobCache.create(id, file, base64);
            blobCache.add(blobInfo);

            /* call the callback and populate the Title field with the file name */
            cb(blobInfo.blobUri(), {
              title: file.name
            });
          };
          reader.readAsDataURL(file);
        };

        input.click();
      },
      image_advtab: true,
      external_plugins: {
        // 'powerpaste': 'https://www.server.com/application/external_plugins/powerpaste/plugin.js'
      },
      content_css: [
        '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
        '//www.tiny.cloud/css/codepen.min.css',
        '<?php echo $base_url; ?>/css/styles.css',
      ],
      relative_urls: true,
      image_caption: true,
      content_style: '.mce-annotation { background: #fff0b7; } .tc-active-annotation {background: #ffe168; color: black; }',
    });
  </script>

<?php } ?>


<!-- DELETE BUTTON PROMPT -->
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Confirm Delete</h4>
      </div>

      <div class="modal-body">
        <p>Are you sure you want to delete this item?</p>
        <!--<p class="debug-url"></p>-->
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <a class="btn btn-danger btn-ok">Delete</a>
      </div>
    </div>
  </div>
</div>
<!--<a href="#" data-href="/delete.php?id=23" data-toggle="modal" data-target="#confirm-delete">Delete record #23</a><br />
  <button class="btn btn-default" data-href="/delete.php?id=54" data-toggle="modal" data-target="#confirm-delete">Delete record #54</button>-->
<script>
  $('#confirm-delete').on('show.bs.modal', function(e) {
    $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
    $('.debug-url').html('Delete URL: <strong>' + $(this).find('.btn-ok').attr('href') + '</strong>');
  });
</script>
<!-- // END DELETE BUTTON PROMPT -->

<script>
  function AutoGrowTextArea(textField) {
    if (textField.clientHeight < textField.scrollHeight) {
      textField.style.height = textField.scrollHeight + "px";
      if (textField.clientHeight < textField.scrollHeight) {
        textField.style.height =
          (textField.scrollHeight * 2 - textField.clientHeight) + "px";
      }
    }
  }

  $(document).ready(function() {
    $('#mydivfortextarea').on('click', 'textarea', function() {
      $(this).height(0).height(this.scrollHeight);
    }).find('textarea').change();
  });
</script>


</div>
<!-- /page content -->

</div>
<!-- /page container -->
</body>

</html>

<?php
$mysqli->close();
ob_flush();
