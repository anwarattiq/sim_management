/* Simple AJAX Code-Kit (SACK) v1.6.1 */
function getInfoFromPHP(myVar) {
  var netID = myVar;
  $.ajax({
    url: "getInfo.php",
    type: "POST",
    data: {
      networkID: netID
    },
    success: function(html) {
      $('#contents').empty();
      $('#contents').load(html);
    }
  });
};

  /******************************
  @@@ GET EMAIL LIST  @@@
   ******************************/
  function ajax_email_list(list_id) {
    var url_path = window.location.hostname;
  	if (url_path.match('127.0.0.1')) url_path = url_path + '/businessintelligence/admin';

    var xhr;
    if (window.XMLHttpRequest) { // Mozilla, Safari, ...
      xhr = new XMLHttpRequest();
    } else if (window.ActiveXObject) { // IE 8 and older
      xhr = new ActiveXObject("Microsoft.XMLHTTP");
    }

    var data = "ajax_action=email_list&list_id=" + list_id +"";
    xhr.open("POST",
      "http://" + url_path + "/internal_request.php",
      true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send(data);
    xhr.onreadystatechange = email_lislt_;

    function email_lislt_() {
      if (xhr.readyState == 4) {
        if (xhr.status == 200) {
          var status        = xhr.responseText; //alert(xhr.responseText);

          if ( status.match(/@@@@@/g) ){
            var status_array  = status.split('@@@@@')
            if (status_array[0]!='') document.getElementById('from_name').value  = status_array[0];
            if (status_array[1]!='') document.getElementById('from_email').value  = status_array[1];
          } else {
            document.getElementById('from_name').value  = '';
            document.getElementById('from_email').value  = '';
          }

        } else {
          alert('There was a problem with the request.');
        }
      }
    }
  }//function

  /******************************
  @@@ GET EMAIL TEMPLATE  @@@
   ******************************/
  function ajax_email_template(template_id) {
    var url_path = window.location.hostname;
  	if (url_path.match('127.0.0.1')) url_path = url_path + '/businessintelligence/admin';

    var xhr;
    if (window.XMLHttpRequest) { // Mozilla, Safari, ...
      xhr = new XMLHttpRequest();
    } else if (window.ActiveXObject) { // IE 8 and older
      xhr = new ActiveXObject("Microsoft.XMLHTTP");
    }

    var data = "ajax_action=email_template&template_id=" + template_id +"";
    xhr.open("POST",
      "http://" + url_path + "/internal_request.php",
      true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send(data);
    xhr.onreadystatechange = email_lislt_;

    function email_lislt_() {
      if (xhr.readyState == 4) {
        if (xhr.status == 200) {
          var status        = xhr.responseText; //alert(xhr.responseText);

          // document.getElementById('email_template_preview').innerHTML  = status;
          // document.getElementById('email_template_preview').contentDocument = '';
          $("#email_template_preview").contents().find("body").html('');
          document.getElementById('email_template_preview').contentDocument.write(status);

        } else {
          alert('There was a problem with the request.');
        }
      }
    }
  }//function
