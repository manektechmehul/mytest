$(document).ready( function() {
	$('a.commentReply').click( function () {

		var comment_id = $(this).attr('val');
		var membername = $('#membername').text();
		var form_id = $('input[name=form_id]').val();
		form = '<form style="margin-top:10px;" method="post" action="">' +
			'<label style="width:90px;" for="comment_name">Screen name</label> '+membername+
			'<br />' +
			'<label style="width:90px;" for="comment">Comment</label> <textarea name="comment" style="width:570px; height:112px;" cols="40"></textarea>' +
			'<input type="hidden" name="comment_id" value="'+comment_id+'"/>' +
			'<input type="hidden" name="reply_form_id" value="'+form_id+'"/>' +
            '<input type="hidden" name="action" value="comment"/>' +
			'<div style="margin:5px 0 0;"><input style="margin-left:90px;" type="submit" name="submit_comment" value="Leave Reply" class="comment_button_reply" /></div>' +
			'</form>';
		$(form).appendTo($(this).parent());
		$(this).remove();
		return false;
	})
	
	
	
	
});

function addnotice(){
	$('.addnotice').show();	
	$('.addnotice_title').show();
}