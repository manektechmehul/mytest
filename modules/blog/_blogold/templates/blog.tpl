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
        <h3 class="blogtitle"><a href='{$posts[posts].link}'>{$posts[posts].title}</a></h3>
        <div class="socialmediafloat" style="margin-top:-28px;">
            {twitter link="`$site_address``$posts[posts].link`" image="/images/share-single-tw.gif" class="socialmedia"}
            {facebook link="`$site_address``$posts[posts].link`" text=$posts[posts].title image="/images/share-single-fb.gif" class="socialmedia"}
            <a href="{$posts[posts].email_post_link}"><img src="/images/share-single-e.gif" class="noborder" alt="Email a friend" border="0" /></a>
        </div>
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
	<h3 class="blogtitle">{$post.title}</h3>
    <div class="socialmediafloat" style="margin-top:-28px;">
        {twitter link="`$site_address``$pageName`" image="/images/share-single-tw.gif" class="socialmedia"}
        {facebook link="`$site_address``$pageName`" text=$post.title image="/images/share-single-fb.gif" class="socialmedia"}
    <a href="{$email_post_link}"><img src="/images/share-single-e.gif" class="noborder" alt="Email a friend" border="0" /></a>
    </div>
    <p class="post_date">Posted by {$post.firstname} {$post.surname} on {$post.date|format_date:"jS F Y"}</p>
	<span class='post_body'>{$post.post}</span>
    
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
    <form method="post" action=''>
	<label for="comment_name">Name:</label> <input type="text" name="comment_name" style="width:300px;"/>
	<label for="comment">Comment:</label> <textarea name="comment" cols=30 rows=7 style="width:300px;"></textarea>
    <label for="captcha">Security:</label> 
					<span id="captcha_flag" style="display:none;"></span>
					<span id="captchaImg"></span>
					<img style="padding-left:5px;" class="noborder" src="/php/securimage/securimage_show.php"><br />
					<input style="width:224px;" type="text" name="captcha" />
	
    <input type="hidden" name="form_id" value="{$form_id}"/>
    <input type="submit" name="submit_comment" value="Submit Comment" class="blogbutton" />
	</form>
	
	</div>
    <hr />
    <p class="csbutton"><a href="{$blog_url}">< Back </a></p>
{/if}
{if $message}  
    {$message}  
{/if}
{if $email_id} 
    <h3>Email to a friend: {$post_title}</h3>
    <p><form method="post" action=''>
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
    <p class="csbutton"><a href='javascript:history.go(-1)'>Back to post</a></p>
{/if}