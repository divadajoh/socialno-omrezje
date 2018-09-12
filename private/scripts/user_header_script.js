/*
|| Script by David Hoja ||
*/

var notf_number = 0;
var msg_number = 0;
var mouse_enter_flag = false;

$(document).ready(function(){
	// == NOTIFICATION BUTTON == //
	$('#notificationsButton').mouseenter(function(){
		$('#notificationsButton p').text(notf_number);
	});

	$('#notificationsButton').mouseleave(function(){
		$('#notificationsButton p').text("Notifications");
	});

	// == MESSAGES BUTTON == //
	$('#messagesButton').mouseenter(function(){
		$('#messagesButton p').text(msg_number);
	});

	$('#messagesButton').mouseleave(function(){
		$('#messagesButton p').text("Messages");
	});


	$('#searchResults').mouseleave(function(){
		$('#searchResults').fadeTo('slow', 0);
		setTimeout(function(){
			$(this).css('display', 'none');
		},1000);
		$('#searchInput').val('');
		$('#searchResults').css('overflow','hidden');
	});




	// || SEARCH HANDLING || //
	$('#searchInput').keypress(function(event){
		var enteredText = $(this).val();
		
		$.ajax({
			url:'private/includes/search.php',
			method:'POST',
			dataType: 'json',
			data: {
				'search_string': enteredText
			},

			success: function(callbackData){
				$('#searchResults').html('');
				$.each(callbackData, function(index, element){
					var is_friend = "";
					if(element.Friendship_Status == true) {
						is_friend = '<p style="float:right; color:green; line-height:3px">(friend)</p>'
					}
					// || Show search results || 
					var html_div_start = "<div class='searchResultDiv' onclick=\"location.href='profile.php?profile_id="+element.ID+"'\">";
					var html_div_end = "</div>";
					var html_img = "<img class='searchResultImg'";
					html_img += " src='private/users/"+element.ID+"/"+element.Profilna_Slika+"'></img>";
					var html_name = "<p class='searchResultName'>" +element.Ime + " " + element.Priimek + '' + is_friend + "</p>";
					//var html_pbutton = "<div class='vpButton' onclick=\"location.href='profile.php?profile_id="+element.ID+"'\"><p>Profile</p></div>";
					$('#searchResults').append(html_div_start + html_img + html_name + html_div_end);

					var chSize = $('#searchResults').children().size();    
					var new_height = chSize * 70;
					if(chSize <= 6) {
						$('#searchResults').css('height', 70 * chSize);
						$('#searchResults').css('overflow', 'default');

					}else {
						$('#searchResults').css('overflow', 'auto');
						$('#searchResults').css('height', 70 * 6);
					}

				});
			}


		}); // || AJAX END || //

		if($('#searchResults').children().size() > 0){$('#searchResults').fadeTo('fast', 1);}
		else{$('#searchResults').fadeTo('fast', 0); $('#searchResults').empty();} 


	});

});



