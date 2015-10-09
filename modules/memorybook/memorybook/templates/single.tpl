<div id="socialmediafloat">
    {googlep link="`$site_address``$pageName`" image="/images/share-single-g.gif" class="socialmedia"}
    {linkedin link="`$site_address``$pageName`" image="/images/share-single-li.gif" class="socialmedia"}
    {twitter link="`$site_address``$pageName`" image="/images/share-single-tw.gif" class="socialmedia"}
    {facebook link="`$site_address``$pageName`" text=$singleitem.title image="/images/share-single-fb.gif" class="socialmedia"}
</div>
<div class="{$listName}singleitem">{show_thumb filename=$singleitem.thumb size='300x700' alt='alt="'$singleitem.title'" class="right" border="0"'}{$singleitem.body}</div>
<p class="csbutton">
    <a {if $button_link == "do_js_back"} onclick="javascript:history.back(-1);" href="#" {else}  href='{$button_link}' {/if} >{$button_text}</a>
</p>
{if $singleitem.allow_comments == '1'}
    <h3>Shared Memories</h3>
    {section name=m loop=$memories}
        <div>
            <h2> My Memory by {$memories[m].name}</h2>

            <p>{$memories[m].memory}</p>
        </div>
        {sectionelse}
        <p> Be the first to add a memory</p>
    {/section}




    {if $memory_added == true }
        <p style="background: #cde;">
            You memory will be checked by the administrator and displayed once vetted. Thanks.
        </p>

    {else}




    <hr>
    <h2>Add your Memory</h2>
    <p> Wouldn't it be awesome to allow photo uploads, that then can be viewed in a gallery ?!</p>
    <form action="" method="post">
        <label> Your Name </label>
        <input value="" name="name"> <br>
        <label> Your email address </label>
        <input value="" name="email"> <br>
        <label> Your Memory </label>
        <textarea name="memory"></textarea> <br>
        <input type="hidden" name="add_memory" value="true">
        <input type="submit" value="Add your Memory">
    </form>
{literal}
    <script type="text/javascript">//<![CDATA[
        window.CKEDITOR_BASEPATH = "/admin/ckeditor/";
        //]]></script>
    <script type="text/javascript" src="/admin/ckeditor/ckeditor.js?t=C9A85WF"></script>
    <script type="text/javascript">//<![CDATA[
        CKEDITOR.replace("memory",
                {
                    customConfig: "/admin/ckeditor/config-simple.js"
                });
        //]]></script>
{/literal}


    {/if}



{/if}



