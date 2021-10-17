	/******************************
	 @@@ VALIDATE CONTACT FORM @@@
	 ******************************/
	function validateContactForm() {
		var full_name = document.getElementById('full_name');
	  	var email 	= document.getElementById('email');
	  	var mobile 	= document.getElementById('mobile');
	  	var location 	= document.getElementById('location');
	  	var message 	= document.getElementById('message');


		if (full_name.value == '') {
			alert('Please enter your Full Name');
			full_name.focus();

		} else if (email.value == '') {
			alert('Please enter your Email');
			email.focus();

		} else if (message.value == '') {
			alert('Please enter Message');
			message.focus();

		} else if (document.getElementById('g-recaptcha-response').value == '') {
			alert("Please verify I'm not a robot.")

		} else {
				// submitContactForm(full_name.value, email.value, mobile.value, location.value, message.value);
		document.getElementById('frmcontact').submit();
		}

	}//function


	/******************************
	 @@@ VALIDATE ADD COMPANY @@@
	 ******************************/
	function validateAddCompany() {
	  var company_name 					= document.getElementById('company_name');
		var company_category 			= document.getElementById('company_category');
		var company_profile 			= document.getElementById('company_profile');
	  var contact_name 					= document.getElementById('contact_name');
		var email 								= document.getElementById('email');
		var telephone 						= document.getElementById('telephone');
		var address 							= document.getElementById('address');
		var pobox 								= document.getElementById('pobox');
		var fax 									= document.getElementById('fax');
		var website 							= document.getElementById('website');
		var city 									= document.getElementById('city').options[document.getElementById('city').selectedIndex].value;
		var country 							= document.getElementById('country').options[document.getElementById('country').selectedIndex].value;

	  if (company_name.value == '') {
	    alert('Please enter Company Name');
	    company_name.focus();

		} else if (company_category.value == '') {
	    alert('Please enter Category');
	    company_category.focus();

		} else if (contact_name.value == '') {
	    alert('Please enter Contact Person');
	    contact_name.focus();

		} else if (email.value == '') {
	    alert('Please enter Email');
	    email.focus();

		} else if (telephone.value == '') {
	    alert('Please enter telephone ');
	    telephone.focus();

	  } else if (address.value == '') {
	    alert('Please enter Address');
	    address.focus();

	  } else {
			// submitAddCompany(company_name.value, contact_name.value, company_category.value, address.value, pobox.value, telephone.value, fax.value, email.value, website.value, city, country, company_profile.value, products.value, services.value);
      document.getElementById('frmaddcompany').submit();
	  }

	}//function


  /******************************
   @@@ VALIDATE ADD COMPANY @@@
   ******************************/
  function validateAddComment() {
    var permalink 				= document.getElementById('permalink');
    var full_name 				= document.getElementById('full_name');
    var email 						= document.getElementById('email');
    var website 					= document.getElementById('website');
    var comment 					= document.getElementById('comment');

    if (full_name.value == '') {
      alert('Please enter Full Name');
      full_name.focus();

    } else if (email.value == '') {
      alert('Please enter Email');
      email.focus();

    } else if (comment.value == '') {
      alert('Please enter Comment');
      comment.focus();

    } else {
      // submitAddComment(''+permalink.value+'', ''+full_name.value+'', ''+email.value+'', ''+website.value+'', ''+comment.value+'');
      document.getElementById('frmaddcomment').submit();
    }

  }//function