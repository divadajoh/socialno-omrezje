/*
|| Script by David Hoja ||
__________________________
*/


/* || SCROLL DOWN WINDOW || */
var scrollPixelCounter = 0;
var scrollDelta = 6;

function scrollDownToRegister() {
	if(scrollPixelCounter <= 800){
		window.scrollBy(0,6);
		scrollPixelCounter+=6;
		delay=setTimeout('scrollDownToRegister()', 3);
	}else{scrollPixelCounter = 0; return;}
}


$(document).ready(function(){
	// == LOGIN ERROR HANDLING == //
	var errMessageVal = $('#loginErrMessage').html();
	if(errMessageVal.length < 10){
		$('#loginErrMessage').hide();
	}else {
		$('#loginErrMessage').show();
	}
	// == END OF LOGIN ERROR HANDLING == //

	/* || REGISTER TEXT ANIMATION || */
	var animationRunning=false;
	$("#registerForm").mouseenter(function(){
		
		if(!animationRunning) {
			$firstMessage = "Welcome, to my little experiment.";
			$('.registerTitle').text($firstMessage).fadeTo('slow', 1).delay(4000).fadeTo('slow', 0);
			setTimeout(function(){
				$('.registerTitle').text("You can enter an imaginary email adress.").fadeTo('slow', 1).delay(4000).fadeTo('slow', 0);
			}, 6000);
			setTimeout(function() {
				$('.registerTitle').text("No confirmation email will be sent to your email account.").fadeTo('slow', 1).delay(3000).fadeTo('slow', 0);
			}, 12000);
			setTimeout(function() {
				$('.registerTitle').text("Having a question? Click 'Contact' in the upper left corner.").fadeTo('slow', 1).delay(3000).fadeTo('slow', 0);
			}, 17000);	
			animationRunning=true;
		}
	});
	/* || REGISTER TEXT ANIMATION END || */
});
