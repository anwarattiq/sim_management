// COUNT CHARS
function char_count(input_name) {
    // alert(input_name);

    var total_chars = 0;
    var input_value	 	= document.getElementById(input_name).value;

    total_chars = input_value.length;

    document.getElementById('span_'+input_name+'').innerHTML = total_chars;
 }
