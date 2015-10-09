 <div id="blog-search">
      <form action="results" method="get" class="search">      
        <label class="form-label" for="keywords" >Search</label>
        <input type="text" id="keywordsearch" name="keywords" />  
        
        <input type="submit"   name="search" value="GO" class="searchbutton">
         
      </form>
</div>

<br>

{if $post_list}
    <h3 style="font-size:23px; line-height:23px; border-bottom:1px solid #ccc; margin:0 0 20px; color:#777;">Latest posts</h3>
    <div id="sidenav">
        <ul>
            {section name=posts loop=$post_list} 
                <li><a href="{$post_list[posts].link}"><span class="post_date">{$post_list[posts].date|format_date:'jS M Y'}</span><br />
                        {$post_list[posts].title}</a></li>
                    {/section}
            <li><a href="{$blog_archive_link}">View Blog Archive</a></li>
        </ul>
    </div>
{/if}
