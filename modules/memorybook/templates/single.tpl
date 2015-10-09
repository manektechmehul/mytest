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
  <div class="undermemoryleft">
    <p class="memorymaindesctext"><strong>If you would like to share a memory about {$singleitem.title}, then leave a message at the bottom of the page. You can share this tribute on your social media page by clicking the buttons below. If you would like to support Rotherham Hospice then find out more about our Tree of Life sculpture...</strong></p>
    <div class="socialmediafloat">
      {facebook link="`$site_address``$pageName`" text=$singleitem.title image="/images/icon-t-facebook.png" class="socialmedia"}
      {twitter link="`$site_address``$pageName`" image="/images/icon-t-twitter.png" class="socialmedia"}
      {linkedin link="`$site_address``$pageName`" image="/images/icon-t-linkedin.png" class="socialmedia"}
      {googlep link="`$site_address``$pageName`" image="/images/icon-t-googleplus.png" class="socialmedia"}
    </div>
    <p class="csbutton"><a href="/memory-book">{$button_text}</a></p>
  </div>
  <div class="undermemoryright">
    <a class="treepromo-holder" href="/tree-of-life" title="Remember your loved one and support Rotherham Hospice">
      <div class="caption-txt">
        <h3>Donate a leaf in memory of a loved one and support Rotherham Hospice</h3>
      </div>
    </a>
  </div>
  <div class="clearfix"></div>

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
{/literal}
    {/if}
{/if}
</div>