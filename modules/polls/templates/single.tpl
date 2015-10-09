<h3>{$question}</h3>
{section name=response loop=$poll_responses}
<div class="pollresponseouter">
    <div class="pollresponsetitle">{$poll_responses[response].answer}</div>
    <div class="pollresponseresultouter">
        <div class="pollresponseresult" style="width:{$poll_responses[response].percent}%;"><span class="pollresponseresultnumber">{$poll_responses[response].percent|string_format:"%d"}%</span></div>
    </div>
</div>
{/section}