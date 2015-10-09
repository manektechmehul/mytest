    <div class="shopsearchcontainer blogsearchcontainer">
      <p>Blog Search</p>
      <form action="/blog/ffald-y-brenin/results" method="get" class="search" id="shopsearch">
        <input type="text" id="keywordsearch" name="keywords" class="shopsearch" />
        <input type="submit" name="search" value="GO" class="shopsearchbutton" id='submit-search' hspace="0" vspace="0" border="0" align="top" />
      </form>
      <div class="clearfix"></div>
    </div>
    
{if $post_list}
    <div class="sidenav-container" style="margin-top:0;">
    <h2 class="sectiontitle">Blog Posts</h2>
    <div class="sidenav">
      <ul>
          {section name=posts loop=$post_list} 
          <li><a href="{$post_list[posts].link}"><span class="post_date">{$post_list[posts].date|format_date:'jS M Y'}</span> | {$post_list[posts].title}</a></li>
          {/section}
          <li><a href="/blog">Blog Home</a></li>
          <li><a href="{$blog_archive_link}">View Blog History</a></li>
      </ul>
    </div>
    </div>
{/if}