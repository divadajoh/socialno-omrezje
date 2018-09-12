/*
  || Script by David Hoja || 
*/
triggerConversationDelete = false;
hiddenContact = null;

$(document).ready(function(){
	scrollToBottom();
	$('input[name=content]').focus();
	// == SAVE MESSAGES // 
	$(document).on('submit','#messageForm', function(e){
		var $form = $(this);
		var serializedData = $form.serialize();
		$("input[name=content").val('');

		$.ajax({
			url:'private/message_handler.php',
			type:'POST',
			data:serializedData,

			success: function(data){
				if(data.indexOf('false') > -1) {
					alert('error');
				}
			}
		});

		return false;
	});

    // || DELETE CONVERSATION BUTTON || //
	$('.contact.selected').mouseenter(function(){
		triggerConversationDelete=true;
		$(this).children().filter('#mDelete').css({
			'display': 'inherit',
			'position': 'absolute',
			'right': '5px',
			'top' : '15px',
			'width' : '25px',
			'height' : '25px'
		});
	});

	$('.contact.selected').mouseleave(function(){
		triggerConversationDelete=false;
		$(this).children().filter('#mDelete').css({
			'display': 'none'
		});
	});

	$('.selected #mDelete').click(function(e){
		$(this).parent().css('opacity', 0).hide();
		hiddenContact = $(this).parent();
		var output="<div class='contact' style='margin-top:20px; height:50px'><span>";
		output+="Delete the conversation?";
		output+="</span>";
		output+="<div id='deletePos'><span>Yes</span></div><div id='deleteNeg'>";
		output+="<span>No</span></div>";
		$(output).insertAfter($(this).parent().prev());

	});

	// == HANDLING THE DELETE YES/NO BUTTONS == //
	$(document).on('click', '#deletePos',function(){
		var tempURL = window.location.href;
		var tempURL = tempURL + '&delete=true';
		location.href=tempURL;
	});
	$(document).on('click', '#deleteNeg',function(){
		$('#deleteNeg').parent().remove();
		if(hiddenContact) {
			hiddenContact.fadeTo('slow',1);
			setTimeout(function(){
				hiddenContact.show();
			}, 1000);
		}
	});

	$('#newConversationButton').click(function(){
		var searchBox = "<input type='text' name='srch' id='searchUsers' placeholder=' Search for friends'>";
		var cancelButton = "<div id='cancelSearch' title='Cancel search' onclick='cancelSearch()'><img src='public_images/cancel_icon.png'></img></div>";
		var iButton = $(this);
		iButton.parent().children().fadeTo('fast', 0, function(event){
			iButton.parent().children().hide();
		});

		setTimeout(function(){
			iButton.parent().append(searchBox).children('#searchUsers').css('opacity', 0).fadeTo('fast',1);
			$(cancelButton).insertAfter('#searchUsers');
			$('#searchUsers').focus();
			$('#searchUsers').keypress(function(event){
				searchValue = $(this).val();
				searchContacts(searchValue);
			});
		},700);

	});




	// == CHECK FOR MESSAGES == //
	checkMessages();
});


function searchContacts(searchValue) {
	$.ajax({
		url:'private/includes/search_friends.php',
		type:'POST',
		dataType:'JSON',
		data: {
			'search_string':searchValue
		},

		success: function(callbackData) {
			$.each(callbackData, function(index, element){
				var result_start= "<div class='srchResult' onclick='contactFound("+element.ID+")'>";
				var html_img = "<img class='searchResultImg'";
				var name="<span>"+element.Ime+ ' ' + element.Priimek + '</span>';
				html_img += " src='private/users/"+element.ID+"/"+element.Profilna_Slika+"'></img>";
				var result_end ="</div>";
				$('#contactContainer').append(result_start+html_img+name+result_end);

			});
		}

	});

	$('#contactContainer').children('.srchResult').remove();


}


function checkMessages() {
	var $form = $('#messageForm');
	var serializedData = $form.serialize();

	$.ajax({
		url:'private/check_messages.php',
		type:'POST',
		data:serializedData,
		dataType:'JSON',

		success: function(callbackData) {
			var lastMessageID = '#';
			var lastMessageSender = ''
			$.each(callbackData, function(index, element){

				if($('.msgbox').size() < 2) {
					var selectedID = $('#contactContainer .selected').attr('onclick');
					selectedID = selectedID.charAt(selectedID.length-2);
					if(element.person_one_id == selectedID) {
						if(element.message != 'lastMessage'){
						var profilePicture = $('#contactContainer .selected img').attr('src');
						var fullname = $('#contactContainer .selected span').text();
						var output="<div class='msgbox'>";
						output+= "<img src='"+profilePicture+"'></img>";
						output+="<span style='padding-left:4px'>"+fullname+"</span>";
						output+="</div> <span class='message' id='"+element.id+"'>"+element.message+"</span>";
						$('.messageArea').append(output);
						}

					}else {
						if(element.message != 'lastMessage'){
						var profilePicture = $('#profile_pic').attr('src');
						var fullname = $('#uhfName').text();
						var output="<div class='msgbox'>";
						output+= "<img src='"+profilePicture+"'></img>";
						output+="<span style='padding-left:4px'>"+fullname+"</span>";
						output+="</div> <span class='message' id='"+element.id+"'>"+element.message+"</span>";
						$('.messageArea').append(output);
						}
					}
				}else{
				
				if(index == 0){
					lastMessageID += parseInt(element.id);
					 lastMessageSender = element.person_one_id;}
				else 
				{
					lastMessage = $(lastMessageID);

					// == IF THIS USER IS THE ONE WHO SENT THE LAST MESSAGE == //
					if(lastMessageSender == element.person_one_id) {
						var output = "<span class='message'";
						output += " id='"+element.id+"'>"+element.message+"</span>";
						$(output).insertAfter(lastMessage);
					}

					// == IF THE INCOMING MESSAGE IS NOT FROM THE LAST MESSAGE SENDER == //
					if(lastMessageSender != element.person_one_id) {
						var msgBoxObj = $('.msgbox');
						                                 //BeforeLast//
						var profilePicture = $('.msgbox').eq(-2).children().eq(0).attr('src');
						var senderFullName = $('.msgbox').eq(-2).children().eq(1).text();
						lastMessage.removeClass('last');
						var output="<div class='msgbox'>";
						output+= "<img src='"+profilePicture+"'></img>";
						output+="<span style='padding-left:4px'>"+senderFullName+"</span>";
						output+="</div> <span class='message' id='"+element.id+"'>"+element.message+"</span>";
						$(output).insertAfter($('span').last());
					}
					scrollToBottom();
				}

			}//ENDOASDSAD

			});
		}

	});

	setTimeout('checkMessages()', 5000);
}

function contactFound(id) {
	$("input[name=pTwoID").val(id);
	$('#chkConv').children().eq(2).click();
}


function scrollToBottom() {
	var container = $('.messageArea');
  	var height = container[0].scrollHeight;
  	var offset = 20;
  	container.scrollTop(height+offset);
}

function cancelSearch() {
	$('#contactContainer').children().not('#newConversationButton, .contact').remove();
	$('.contact, #newConversationButton').show().fadeTo('fast', 1);
}