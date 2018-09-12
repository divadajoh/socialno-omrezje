/*
  || Script by: David Hoja ||
*/

// == VARIABLES == //
var picturePreview = 0; // Small picture
var visibleTo = 0; // 0 -Friends 1 - Public 2 - Only me
var updateContentBig = false;
var visibleOption = 0; // 0 - None, 1 - Profile update, 2 - Upload picture
var photoVisibleTo = 0;



$(document).ready(function(){


	$('.friendshipButton').click(function(){
		sendFriendRequest();
	});

	$('#writePost').click(function(){
		if(visibleOption == 2){
		 	visibleOption = 1;
		 	$('#pictureUpload').css("display", "none");
		}
		$('#statusUpdate').css("display", "inherit").fadeTo('slow', 1); 2;
		visibleOption = 1;
	});

	$('#uploadPicture').click(function(){
		if(visibleOption == 1){
		 	visibleOption = 2;
		 	$('#statusUpdate').css("display", "none");
		}
		$('#pictureUpload').css("display", "inherit").fadeTo('slow', 1);
		visibleOption = 2;
	});


	$('#choosePic').click(function(){
		$('input[name=imageUpload]').click();
	});

	// == TRIGGERS FILE UPLOAD BOX == //
	$('input[name=addPicture]').click(function(){
		$('input[name=fileUpload]').click();
	});

	// == CHANCING PRIVACY SETTING ID's == //
	$('input[name=privacySettings]').click(function(){
		if(visibleTo < 2) {
			visibleTo++;
		}else {
			visibleTo = 0;
		}

		var outputText = "Visible to: ";

		switch(visibleTo) {
			case 0:
			outputText+="Friends";
			break;
			case 1:
			outputText+="Public";
			break;
			case 2:
			outputText+="Only me";
			break;
		}

		$(this).val(outputText);
		$('input[name=privacySettingID]').val(visibleTo);
	});


	// == CHANGING PHOTO PRIVACY SETTINGS == //
	$('input[name=privacySettingsButton]').click(function(){
		if(photoVisibleTo < 2) {
			photoVisibleTo++;
		}else {
			photoVisibleTo = 0;
		}

		var outputText = "Visible to: ";

		switch(photoVisibleTo) {
			case 0:
			outputText+="Friends";
			break;
			case 1:
			outputText+="Public";
			break;
			case 2:
			outputText+="Only me";
			break;
		}

		$(this).val(outputText);
		$('input[name=picturePrivacyID]').val(photoVisibleTo);
	});


	// == MAKES THE IMAGE LARGER == //
	$('#imagePreview').click(function(){
		if(picturePreview === 0){
		$(this).animate({
			width:"70%",
			height:"200px",
			marginRight:0
		},1000,function(){
			picturePreview = 1;
		});

		} else {
			$(this).animate({
			width:"40px",
			height:"40px",
			marginRight:"60%"
		},1000,function(){
			picturePreview = 0;
		});
		}

});


	// == RESIZES THE CONTENT BOX == //
	$('textarea[name=content]').click(function(){
		if(!updateContentBig){
			$(this).animate({height:"110px"},500);
			updateContentBig = true;
		}else {
			$(this).animate({height:"50px"},500);
			updateContentBig = false;
		}
	});
});


function sendFriendRequest() {
	var rec_id = window.location.href;
	rec_id = rec_id.charAt(rec_id.length-1);
	$.ajax({
		url: 'private/profile_handler.php',
		type: 'POST',
		data: {'Friend_Request': rec_id},

		success: function(callbackData) {
			if(callbackData.indexOf('FriendRequestSent') > -1) {
				$('.friendshipButton p').text('Friend request sent').css({'color':'gray'});
				$('.friendshipButton').addClass('requestSent');
			}else {
				alert('ni uspelo');
			}
		}

	});
}

// == LOADS THE PICTURE FOR PREVIWEING == //
var loadPicture = function(event) {
    var output = document.getElementById('imagePreview');
    output.src = URL.createObjectURL(event.target.files[0]);
    $('#imagePreview').css({'display':'inherit', 'margin-left':'15%'}).fadeTo('slow', 1);
    $('input[name=addPicture]').prop("disabled", true);
}


var loadPictureSecond = function(event) {
    var output = document.getElementById('imgPreview');
    output.src = URL.createObjectURL(event.target.files[0]);
   	
   	$('<br><br>').insertAfter('input[name=uploadPicture]');
    $('#imgPreview').css('display','inherit').fadeTo('slow', 1);
    $('#choosePic').css('display', 'none');
    $('#imgPreview').animate({
    	height: "250px",
    	width: "95%"
    }, 800, function(){
    	$('input[name=caption], input[name=uploadPicture],input[name=privacySettingsButton]').css('display','inherit').fadeTo('slow', 1);
    });
}
