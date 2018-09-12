/*
  || Script by David Hoja || 
*/

$(document).ready(function(){
	var profile_pic_url = $('.profilePic img').attr('src');
	$('.profilePic').mouseenter(function(){
		$obj = $(this);
		$obj.empty();
		var html_p = '<p>Upload?</p>';
		$obj.append(html_p);
		$obj.css('border', '2px solid #29A3CC');
	});


	$('.profilePic').mouseleave(function(){
		$obj = $(this);
		if(typeof(profile_pic_url) != 'undefined')
		{
			$obj.empty();
			var html_img = "<img src='" + profile_pic_url + "'></img>";
			$obj.append(html_img);
			$obj.css('border', '2px solid #29A3CC');
		}else 
		{
			$obj.empty();
			var html_p = '<p>Upload picture</p>';
			$obj.append(html_p);
		}
	});

	$('.profilePic').click(function(){
		$('input[name=fileUpload').click();
	});


	$('input[name=fileUpload').change(function(){
		$('.imgMessage').remove();
		$('<p class="imgMessage" title="Click \'Save\' for the picture to be uploaded.">Picture saved</p>').insertAfter('.profilePic');
	});


});