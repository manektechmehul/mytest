<div class="memorybookmain memorybookinside">
  <div class="memorybookinsidecontent">
    <h1>{$singleitem.title}</h1>
    <h3>{$singleitem.description}</h3>
    <div class="memorybookimagecontainer">
      <div class="memorybookimage" style="background-image:url({show_thumb_minimal filename=$singleitem.thumb size='600x1000'});">{show_thumb filename=$singleitem.thumb size='600x1000' alt='alt="'$singleitem.title'" border="0"'}</div>
    </div>
    <h2 class="memorybookinsidedate">{$singleitem.date_of_birth|format_date:"jS F Y"} - {$singleitem.date_of_death|format_date:"jS F Y"}</h2>
    <div id="memorybookinsidemaintext" class="nano">
      <div class="nano-content">{$singleitem.body}</div>
    </div>
  </div>
</div>

<div class="undermemory">
<div class="socialmediafloat">
    {facebook link="`$site_address``$pageName`" text=$singleitem.title image="/images/icon-t-facebook.png" class="socialmedia"}
    {twitter link="`$site_address``$pageName`" image="/images/icon-t-twitter.png" class="socialmedia"}
    {linkedin link="`$site_address``$pageName`" image="/images/icon-t-linkedin.png" class="socialmedia"}
    {googlep link="`$site_address``$pageName`" image="/images/icon-t-googleplus.png" class="socialmedia"}
</div>
<p class="csbutton"><a href="/memory-book">{$button_text}</a></p>

{if $singleitem.allow_comments == '1'}
    <h2>Shared memories of {$singleitem.title}</h2>
    {section name=m loop=$memories}
        <div class="memorycomment">
            <p class="memorycommentmain">{$memories[m].memory}</p>
            <p class="memorycommentbase">Memory posted by {$memories[m].name} on {$memories[m].date|format_date:"jS F Y"}</p>
        </div>
        {sectionelse}
        <p>Be the first to add a memory</p>
    {/section}

    {if $memory_added == true }
        <p style="background: #cde;">
            You memory will be checked by the administrator and displayed once vetted. Thanks.
        </p>
    {else}
{literal}
<link href="http://fonts.googleapis.com/css?family=Lusitana:700,400" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="/css/nanoscroller.css">
<script src="/js/jquery.nanoscroller.min.js"></script>
<script>
$(function() {
  $(".nano").nanoScroller();
});
</script>
<!--<link rel="stylesheet" type="text/css" href="/css/perfect-scrollbar.min.css">
<script src="/js/perfect-scrollbar.jquery.min.js"></script>
<script>
$(function() {
    $('#memorybookinsidemaintext').perfectScrollbar();
    // with vanilla JS!
    Ps.initialize(document.getElementById('memorybookinsidemaintext'));
});
</script>
<!--<script>//<![CDATA[
	window.CKEDITOR_BASEPATH = "/admin/ckeditor/";
	//]]></script>
<script src="/admin/ckeditor/ckeditor.js?t=C9A85WF"></script>
<script>//<![CDATA[
	CKEDITOR.replace("memory",
			{
				customConfig: "/admin/ckeditor/config-simple.js"
			});
	//]]></script>-->
{/literal}
    {/if}
{/if}
</div>