/* The XMLHttpRequest Object
The XMLHttpRequest object can be used to request data from a web server.

The XMLHttpRequest object is a developers dream, because you can:

Update a web page without reloading the page
Request data from a server - after the page has loaded
Receive data from a server  - after the page has loaded
Send data to a server - in the background */

var base_url = 'https://www.imranonline.net';
// FOR LOCALHOST
// var base_url = window.location.hostname;
// if (base_url.match('127.0.0.1') || base_url.match('localhost')) {
// 	base_url = "http://" + base_url + "/imranonline";
// }
// alert(base_url);

	//================================================================

	//--------------- SEND ENQUIRY / CONTACT PAGE --------------------
	if (document.getElementById('contactButton')){
		document.getElementById('contactButton').addEventListener('click', send_enquiry);
	//================================================================
		function send_enquiry(e){
			e.preventDefault();

			// var subject 	= document.getElementById('subject').value;
			// var subject 	= document.getElementById("subject");
			// var subject 	= subject.options[subject.selectedIndex].value;
			var full_name 	= document.getElementById('full_name').value;
			var email 		= document.getElementById('email').value;
			var message 	= document.getElementById('message').value;
			var captcha 	= document.getElementById('g-recaptcha-response').value;
			var params 		= "process=send_enquiry" + "&full_name=" + full_name + "&email=" + email + "&message=" + message + "&captcha=" + captcha;

			var xhr = new XMLHttpRequest();
			xhr.open('POST', 'process_ajax.php', true);
			xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
			xhr.send(params);
			// xhr.onreadystatechange = send_enquiry_response;
			xhr.onload = send_enquiry_response;

			//*****************************
			function send_enquiry_response() {
				// OPTIONAL - used for loaders
				// xhr.onprogress = function(){ console.log('READYSTATE: ', xhr.readyState); }

					// console.log('READYSTATE: ', xhr.readyState);
					if (xhr.status == 200){
						// var response = xhr.responseText;
						// alert(xhr.responseText);
						var response = JSON.parse(xhr.responseText);

						if (response['error_message']!=''){
							document.getElementById('message_status').innerHTML = response['error_message'];

						} else if (response['success_message']!=''){
							document.getElementById('message_status').innerHTML = response['success_message'];

							// RESET ALL FIELD VALUES
							document.getElementById('full_name').value = '';
							document.getElementById('email').value = '';
							document.getElementById('message').value = '';

							// REDIRECT TO THANKS PAGE
							window.location.href = base_url + '/contact-thanks';

						}

					} else if (xhr.status = 404){
						// alert('Not Found');
						document.getElementById('message_status').innerHTML = 'Error! Not found.';
					}
				// xhr.onerror = function(){ console.log('Request Error...'); }
			}// end function ready
			//*****************************

		}
	}
	//================================================================



	function trigger_search(e) {
		// alert(e.which);
		if( e.which == 13 ){  // the enter key code
 			search();
	  }
	}


	//---------------------------------- HEADER SEARCH  -------------------------------
	if (document.getElementById('searchHeaderButton')){
		document.getElementById('search-header').addEventListener('keyup', trigger_search);
		document.getElementById('searchHeaderButton').addEventListener('click', search);
	}

	//---------------------------------- MOBILE SEARCH  -------------------------------
	if (document.getElementById('searchMobileButton')){
		document.getElementById('search-mobile').addEventListener('keyup', trigger_search);
		document.getElementById('searchMobileButton').addEventListener('click', search);
	}
	
	//---------------------------------- BLOG PAGE SEARCH  -------------------------------
	if (document.getElementById('searchBlogButton')){
		document.getElementById('search-blog').addEventListener('keyup', trigger_search);
		document.getElementById('searchBlogButton').addEventListener('click', search);
	}

	function search(e) {
		// e.preventDefault();  //prevent form submits:
		var search_keyword = '';


		if (document.getElementById('searchHeaderButton')){
			var header_search_keyword	= document.getElementById('search-header').value;
		}
		if (document.getElementById('searchMobileButton')){
			var mobile_search_keyword	= document.getElementById('search-mobile').value;
		}
		if (document.getElementById('searchBlogButton')){
			var blog_search_keyword	= document.getElementById('search-blog').value;
		}
		

		//---------------------------------- HEADER SEARCH  -----------
		if (header_search_keyword!='' && header_search_keyword!='undefined') {
			search_keyword = header_search_keyword;	
		
		//---------------------------------- MOBILE SEARCH  -----------
		} else if (mobile_search_keyword!='' && mobile_search_keyword!='undefined') {
			search_keyword = mobile_search_keyword;	
		
		//---------------------------------- BLOG PAGE SEARCH  -----------
		} else if (blog_search_keyword!='' && blog_search_keyword!='undefined') {
			search_keyword = blog_search_keyword;
		
		}
		if (search_keyword!='' && search_keyword!=null && search_keyword!='undefined'){
			search_keyword 			= search_keyword.toLowerCase();
			search_keyword			  = search_keyword.replace('/\s+/g', '-'); 	// CONVERT SPACE TO -
			search_keyword			  = search_keyword.replace(' ', '-'); 	// CONVERT SPACE TO -
			search_keyword			  = search_keyword.replace('+', '-'); 	// CONVERT SPACE TO -
			search_keyword			  = search_keyword.replace('"', ''); 	// CONVERT SPACE TO -
			search_keyword			  = search_keyword.replace("'", ''); 	// CONVERT SPACE TO -
			search_keyword			  = search_keyword.replace(' ', '-'); 	// CONVERT SPACE TO -
			search_keyword			  = search_keyword.replace(/[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi, '-');

					// AJAX SAVE SEARCH IN DB
					var params = "process=save_search" + "&search_keyword=" + search_keyword+'';
					var xhr = new XMLHttpRequest();
					xhr.open('POST', ''+ base_url + '/process_ajax.php', true);
					xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
					xhr.send(params);
					xhr.onload = save_search_response;

					//****************************************
					function save_search_response() {
						// OPTIONAL - used for loaders
						// xhr.onprogress = function(){ console.log('READYSTATE: ', xhr.readyState); }
							// console.log('READYSTATE: ', xhr.readyState);
							// if (xhr.status == 200){
							// 	// var response = xhr.responseText;
							// 	// alert(xhr.responseText);
							//
							// 	var response = JSON.parse(xhr.responseText);
							// 	if (response['error_message']!=''){
							// 		alert(response['error_message']);
							//
							// 	} else if (response['success_message']!=''){
							// 		alert(response['success_message']);
							// 	}
							// } else if (xhr.status = 404){
							// 	// alert('Not Found');
							// 	// document.getElementById('message_status').innerHTML = 'Error! Not found.';
							// }
						// xhr.onerror = function(){ console.log('Request Error...'); }
					}// end function ready
					//*****************************
			window.location.href = base_url + '/search/'+ search_keyword; // In case keyword enter
			//**********************************************
		} else {
			window.location.href = base_url + '/search/'; // In case empty keyword enter
		}
	} // function
	//================================================================



		//--------------- SUBMIT COMMENTS / ARTICLE PAGE --------------------
	// if (document.getElementById('commentsButton')){
	// 	document.getElementById('commentsButton').addEventListener('click', post_comments);
	// //================================================================
	// 	function post_comments(e){
	// 		e.preventDefault();

	// 		// var subject 	= document.getElementById('subject').value;
	// 		// var subject 	= document.getElementById("subject");
	// 		// var subject 	= subject.options[subject.selectedIndex].value;
	// 		var article_id 	= document.getElementById('article_id').value;
	// 		var full_name 	= document.getElementById('full_name').value;
	// 		var email 		= document.getElementById('email').value;
	// 		var website 	= document.getElementById('website').value;
	// 		var comments 	= document.getElementById('comments').value;
	// 		var captcha 	= document.getElementById('g-recaptcha-response').value;
	// 		var params 		= "process=post_comments" + "&article_id=" + article_id + "&full_name=" + full_name + "&email=" + email + "&website=" + website + "&comments=" + comments + "&captcha=" + captcha;
			
	// 		alert(params);
	// 		var xhr = new XMLHttpRequest();
	// 		xhr.open('POST', 'process_ajax.php', true);
	// 		xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	// 		xhr.send(params);
	// 		// xhr.onreadystatechange = post_comments_response;
	// 		xhr.onload = post_comments_response;

	// 		//*****************************
	// 		function post_comments_response() {
	// 			// OPTIONAL - used for loaders
	// 			// xhr.onprogress = function(){ console.log('READYSTATE: ', xhr.readyState); }

	// 				// console.log('READYSTATE: ', xhr.readyState);
	// 				if (xhr.status == 200){
	// 					// var response = xhr.responseText;
	// 					// alert(xhr.responseText);
	// 					alert(xhr.responseText);
	// 					var response = JSON.parse(xhr.responseText);

	// 					if (response['error_message']!=''){
	// 						document.getElementById('message_status').innerHTML = response['error_message'];

	// 					} else if (response['success_message']!=''){
	// 						document.getElementById('message_status').innerHTML = response['success_message'];

	// 						// RESET ALL FIELD VALUES
	// 						document.getElementById('full_name').value = '';
	// 						document.getElementById('email').value = '';
	// 						document.getElementById('website').value = '';
	// 						document.getElementById('comments').value = '';

	// 						// REDIRECT TO THANKS PAGE
	// 						// window.location.href = base_url + '/contact-thanks';
	// 						document.getElementById('message_status').innerHTML = 'Thanks. Your comments on this post will be active after review.';

	// 					}

	// 				} else if (xhr.status = 404){
	// 					// alert('Not Found');
	// 					document.getElementById('message_status').innerHTML = 'Error! Not found.';
	// 				}
	// 			// xhr.onerror = function(){ console.log('Request Error...'); }
	// 		}// end function ready
	// 		//*****************************

	// 	}
	// }
	//================================================================