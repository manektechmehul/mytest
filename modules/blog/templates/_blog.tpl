{if $blogs}
{section name=blogs loop=$blogs}
  <div class="listitem">
	<h3><a href="{$blogs[blogs].link}">{$blogs[blogs].title}</a></h3>
    <p>{$blogs[blogs].description}<p>
    <p class="csbutton"><a href="{$blogs[blogs].link}">Read Blog</a> <a href="/rss/blog/{$blogs[blogs].page_name}.xml">Subscribe to RSS</a></p>
  </div>
{/section}
{/if}
{if $posts}
<div class="rssplacement">
	<a href="/rss/blog/{$blog_page_name}.xml" target="_blank"><img border="0" class="noborder" src="/images/share-single-rss.png" alt="blog {$blog_title} rss feed" /></a>
</div>
{section name=posts loop=$posts}
    {if $posts[posts].newgroup}
        <h2 class="blogdate">{$posts[posts].group}</h2>
    {/if}
    <div class='blog_post {if $smarty.section.posts.last}blog_post_last{/if}'>
	<h3 class="blogtitle"><a href='{$posts[posts].link}'>{$posts[posts].title}</a></h3>
    <p class="post_date">Posted by {$posts[posts].firstname} {$posts[posts].surname} on {$posts[posts].date|format_date:"jS F Y"} | <a href='{$posts[posts].link}#comment'>Comments: {$posts[posts].comments}</a></p>
	<div class="newseventsitem">{$posts[posts].post}</div>
    <div class="clearfix" style="margin-top:25px;"></div>
	</div>
{sectionelse}
{$no_posts}
{/section}
{/if}
{if $post}
	<div class='blog_post blog_post_last'>
	<h3 class="blogtitle">{$post.title}</h3>
    <p class="post_date">Posted by {$post.firstname} {$post.surname} on {$post.date|format_date:"jS F Y"}</p>
	<div class="newseventsitem">{$post.post}</div>
      <div class="socialcontainer">
        <hr>
        <p class="small">Like this blog post? Share it with your community...</p>
        <span class='st_facebook_large' displayText='Facebook'></span>
        <span class='st_twitter_large' displayText='Tweet'></span>
        <span class='st_email_large' displayText='Email'></span>
        <!--<span class='st_pinterest_large' displayText='Pinterest'></span>-->
        <!--<span class='st_linkedin_large' displayText='LinkedIn'></span>-->
        <!--<span class='st_googleplus_large' displayText='Google +'></span>-->
      </div>
    
    <hr />

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
    <p><form id="form-table" method="post" action=''>
    <label for="from_name" style="font-size:12px;">Your Name:</label><br /><input type="text" name="from_name" style="width:300px;"/><br /><br />

    <label for="from_email" style="font-size:12px;">Your Email:</label><br /><input type="text" name="from_email" style="width:300px;"/><br /><br />

    <label for="to_email" style="font-size:12px;">Friend's Email:</label><br /><input type="text" name="to_email" style="width:300px;"/><br /><br />

    <label for="message" style="font-size:12px;">Message:</label><br /><textarea name="message" cols=30 rows=7 style="width:300px;"></textarea><br /><br />

    <label for="captcha" style="font-size:12px;">Security:</label><br />
					<span id="captcha_flag" style="display:none;"></span>
					<span id="captchaImg"></span>
					<img src="/php/securimage/securimage_show.php" class="noborder"><br />
					<input style="width:224px;" type="text" name="captcha" /> <br /><br />

	
    <input type="hidden" name="email_id" value="{$email_id}"/>
    <input type="submit" name="submit_email" value="Send email" class="blogbutton" />
    </form></p>
    <p><a href='javascript:history.go(-1)'>Back to post</a></p>
{/if}