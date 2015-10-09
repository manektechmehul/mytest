{if $blogs}
<div class="row">
{section name=blogs loop=$blogs}
  <div class="col-sm-6 col-xs-12">
  <div class="listitem" style="margin-bottom:0;">
	<h3><a href="{$blogs[blogs].link}">{$blogs[blogs].title}</a></h3>
    <p>{$blogs[blogs].description}<p>
    <p class="csbutton"><a href="{$blogs[blogs].link}">Read Blog</a> <a href="/rss/blog/{$blogs[blogs].page_name}.xml" target="_blank">Subscribe to RSS</a></p>
  </div>
  </div>  
{/section}
</div>
{/if}
{if $posts}
<div class="rssfeedlink">
	<a href="/rss/blog/{$blog_page_name}.xml"><img border="0" class="noborder" src="/cmsimages/rss-subscribe.png" alt="blog {$blog_title} rss feed" /></a>
</div>
{section name=posts loop=$posts}
    
   
    
      {if $blog_archive == 1}
          
          {literal}
          <style>
          #content .noalignnoborder,#content .leftnoborder,#content .rightnoborder,#content .noalign,#content .left,#content .right { display: none; }         
          </style>       
          {/literal}
          
          
          
        {if $posts[posts].newgroup}
           
            {if  $smarty.section.posts.index != 0}
                </ul>
            {/if}
            <h2 class="blogdate">{$posts[posts].group}</h2>
            <ul>
        {/if} 
         <li>
        <div class='blog_post {if $smarty.section.posts.last}blog_post_last{/if}'>
            <h3 class="blogtitle"><a href='{$posts[posts].link}'>{$posts[posts].title}</a></h3>   
            <p class="post_date">Posted by {$posts[posts].firstname} {$posts[posts].surname} on {$posts[posts].date|format_date:"jS F Y"} | <a href='{$posts[posts].link}#comment'>Comments: {$posts[posts].comments}</a></p>
            <div>          
              {$posts[posts].post|truncate:200}
            </div>    
	</div>      
         </li>
          
        {if $smarty.section.posts.last}</ul>{/if}  
          
        
        
      {else}
            
    
    {if $posts[posts].newgroup}
        <h2 class="blogdate">{$posts[posts].group}</h2>
    {/if}
    <div class='blog_post {if $smarty.section.posts.last}blog_post_last{/if}'>

<div class="socialmediafloat" style="margin:0;">
{googlep link="`$site_address``$posts[posts].link`" image="/images/share-single-g.gif" class="socialmedia"}{linkedin link="`$site_address``$posts[posts].link`" image="/images/share-single-li.gif" class="socialmedia"}{twitter link="`$site_address``$posts[posts].link`" image="/images/share-single-tw.gif" class="socialmedia"}{facebook link="`$site_address``$posts[posts].link`" text=$posts[posts].title image="/images/share-single-fb.gif" class="socialmedia"}
</div>
	<h3 class="blogtitle"><a href='{$posts[posts].link}'>{$posts[posts].title}</a></h3>
    <p class="post_date">Posted by {$posts[posts].firstname} {$posts[posts].surname} on {$posts[posts].date|format_date:"jS F Y"} | <a href='{$posts[posts].link}#comment'>Comments: {$posts[posts].comments}</a></p>
	<div class="newseventsitem">          
            {$posts[posts].post}
        </div>
    <div class="clearfix" style="margin-top:25px;"></div>
	</div>
            
            
         
            {/if}   
            
            
            
{sectionelse}
{$no_posts}
{/section}
    {if !$archive_pagination  }<p class="csbutton"><a href="{$blog_archive_link}">View Blog Archive ></a></p>{/if}
{/if}


{if $archive_pagination}
    {$archive_pagination}
    {/if}


{if $post}
	<div class='blog_post blog_post_last'>
<div class="socialmediafloat" style="margin:0;">
{googlep link="`$site_address``$pageName`" image="/images/share-single-g.gif" class="socialmedia"}{linkedin link="`$site_address``$pageName`" image="/images/share-single-li.gif" class="socialmedia"}{twitter link="`$site_address``$pageName`" image="/images/share-single-tw.gif" class="socialmedia"}{facebook link="`$site_address``$pageName`" text=$post.title image="/images/share-single-fb.gif" class="socialmedia"}
</div>
	<h3 class="blogtitle">{$post.title}</h3>
    <p class="post_date">Posted by {$post.firstname} {$post.surname} on {$post.date|format_date:"jS F Y"}</p>
	<span class='post_body'>{$post.post}</span>
    
	{section name=comments loop=$comments}
        {if $smarty.section.comments.first}<h3>Comments</h3>{/if}
		<div class='blog_comment'>
            <span class='comment_author'>Comment by {$comments[comments].name} on {$comments[comments].date|format_date:"g.ia jS F Y"}</span>
            <span class='comment_body'>{$comments[comments].comment}</span>
		</div>
    {sectionelse}
	{/section}	
	<h3 style="margin-bottom:10px;"><a name="comment"></a>Leave a comment</h3>
    
    <form id="form-table" method="post" action=''>
      <table border='0' cellspacing='5' cellpadding='3' style='margin:0' id='form-table'>
      <tr valign="middle">
      <td width="22%">
      <div align="right">Name*</div>
      </td>
      <td><input type="text" name="comment_name" /></td></tr>
      <tr valign="middle">
      <td width="22%">
      <div align="right">Comment*</div>
      </td>
      <td><textarea name="comment" ></textarea></td></tr>
      <td width="22%">
        <div align="right">Complete security field*</div>
      </td>
      <td><span id="captcha_flag" style="display:none"></span>
      <span id="captchaImg"></span>
      <img src="/php/securimage/securimage_show.php" style="float:left; width:220px;"><input style="float:left; width:220px; height:40px;" type="text" name="captcha" />
      </tr><tr valign="middle"> 
      <td width="22%">&nbsp;</td>
      <td>
      <input type="hidden" name="form_id" value="{$form_id}"/>
      <input type="submit" name="submit_comment" value="Submit Comment" />
	  </td>
	  </tr>
      </table>
	</form>
	
	</div>
{/if}
{if $message}  
{$message}  
{/if}
{if $email_id} 
    <h3>Email to a friend: {$post_title}</h3>
    <p><form id="form-table" method="post" action=''><br>
    <label for="from_name">Your Name:</label><br /><input type="text" name="from_name" /><br /><br />

    <label for="from_email">Your Email:</label><br /><input type="text" name="from_email" /><br /><br />

    <label for="to_email">Friend's Email:</label><br /><input type="text" name="to_email" /><br /><br />

    <label for="message">Message:</label><br /><textarea name="message" cols=30 rows=7 ></textarea><br /><br />

    <label for="captcha">Security:</label><br />
					<span id="captcha_flag" style="display:none;"></span>
					<span id="captchaImg"></span>
					<input style="float:left; width:220px; height:40px;" type="text" name="captcha" /><img src="/php/securimage/securimage_show.php" class="noborder"> <br /><br />

	
    <input type="hidden" name="email_id" value="{$email_id}"/>
    <input type="submit" name="submit_email" value="Send email" class="blogbutton" />
    </form></p>
    <p class="csbutton"><a href='javascript:history.go(-1)'>Back to post</a></p>
{/if}