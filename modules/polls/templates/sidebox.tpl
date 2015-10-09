
				<div id="pollSideBox" class="sidecol-item">
					<h3>{$poll.question}</h3>
					<form action="" method="post" id="pollSideBoxForm">
					    <input type="hidden" name="pollid" id="pollid" value="{$poll.id}">
						<ul>
                        {section name=answers loop=$poll.answers}
						    <li><input class="pollOption" type="radio" name="answer" value="{$smarty.section.answers.rownum}"><span class="pollpoint" />{$poll.answers[answers]}</span><div class="clearfix"></div></li>
                        {/section}
						</ul>
						<div>
							<input class="formsubmit" id="pollSideBoxButton" type="submit" value="Vote" disabled="disabled">
						</div>
					</form>
				</div>
                <div id="pollSideBoxResponse" style="display: none">
                    <p>Thank you for voting. <a href="/polls/{$poll.id}">See results</a></p>    
                </div>
                <div class="poll-base"></div>
                
<script>
{literal}
$('.pollOption').click( function() {
    $('#pollSideBoxButton').removeAttr("disabled")
})
$('#pollSideBoxForm').submit( function () {
    pollId = $('#pollid').val();
    answerId = $('input:radio[name=answer]:checked').val();
    $.post(
        "/modules/polls/ajax/process.php", 
        {id: pollId, answer: answerId},
        function(data) { $('#pollSideBox').toggle(); $('#pollSideBoxResponse').html(data).toggle();}
    );
    
    return false;
});
{/literal}
</script>