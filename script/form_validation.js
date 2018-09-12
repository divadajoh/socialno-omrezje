function isValidEmail(v) {
    var r = new RegExp("[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?");
    return (v.match(r) == null) ? false : true;
}

function prepareToLogin() {
	// | SCROLL UP THE WINDOW |
	var pixelCounter = 0;
	var pixelLimit = 800;
	while(pixelCounter <= pixelLimit){ 
		window.scrollBy(0,-16);
		pixelCounter+=16;
	}
	// | SET EMAIL |
	$('#emailBox').val($('#rEmailInput').val());
}



$(document).ready(function(){


	// == REGISTRATION FORM CHECKING == //

	$(document).on('submit', '#registrationForm', function(e){

		var $form = $(this);
		var serializedData = $form.serialize();

		var err_flag = false;
		// | CHECK IF THE PASSWORDS MATCH |
		if($('#pass').val() != $('#cPass').val()) {
			$('.registerPassErrMessage').html('<span style="color:red">*Passwords</span> do not match! Check your spelling.').fadeTo('slow', 1)
			err_flag = true;
		}else {
			$('.registerPassErrMessage').fadeTo('slow', 0);
			err_flag = false;
		}

		// | CHECK IF THE PASSWORDS ARE EMPTY
		if($('#pass').val()=="" || $('#cPass').val()=="" || $('#nField').val()=="" || $('#snField').val()=="") {
			var emptyFieldErr = "*You must fill out the<span style='color:red'> all </span> of the fields.";
			$('.registerPassErrMessage').html(emptyFieldErr).fadeTo('slow',1);
			err_flag = true;
		}

		// | CHECK IF THE EMAIL IS A PROPER EMAIL
		if(!isValidEmail( $('#rEmailInput').val() )) {
			$('.registerEmailErrMessage').fadeTo('slow', 1);
			err_flag = true;
		}else {
			$('.registerEmailErrMessage').fadeTo('slow', 0);
			var email = $('#rEmailInput').val();
			err_flag = false;
			
		/* CHECK IF EMAIL IS A VALID ONE| AJAX START */
		$.ajax({
			url: 'private/protection/check_registration_data.php',
			type: 'POST',
			data: serializedData,
			success: function(data) {
				if(data.indexOf('852456') > -1){ 

					var formatedErrMessage = '<span>*This email is already in use! </span>';
					formatedErrMessage+='<a style=\'cursor:pointer; color: blue; background-color:gray; border-radius: 1px;\' onclick=\'';
					formatedErrMessage+='prepareToLogin();\'>Log in?</a>';
					$('.registerEmailErrMessage').html(formatedErrMessage).fadeTo('slow', 1);

				}else if(data.indexOf('628985') > -1){
					$('.registerPassErrMessage').html('<span style="color:red">*Passwords</span> do not match! Check your spelling.').fadeTo('slow', 1);

				}else if(data.indexOf('485431') > -1){
					var emptyFieldErr = "*You must fill out the<span style='color:red'> all </span> of the fields.";
					$('.registerPassErrMessage').html(emptyFieldErr).fadeTo('slow',1);

				}else if(data.indexOf('011110') > -1) {
					$('.registerEmailErrMessage').fadeTo('slow',0);
					var formatedErrMessage = '<span>*Your registration is now complete. </span>';
					formatedErrMessage+='<a style=\'cursor:pointer; color: blue; background-color:gray; border-radius: 1px;\' onclick=\'';
					formatedErrMessage+='prepareToLogin();\'>Log in?</a>';
					$('.registerEmailErrMessage').html(formatedErrMessage).fadeTo('slow', 1);

				}else if(data.indexOf('error') > -1) {
					$('.registerPassErrMessage').html('<span style="color:red">*Sorry, </span> something went wrong when trying to register you.').fadeTo('slow', 1);
				}
						
			}
		});
		/* || AJAX END || */

		
		}
		return false;
	});



});
