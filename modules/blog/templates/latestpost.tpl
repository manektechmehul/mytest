
      <div class="sideboxitem">
        <p class="sideboxitemdate">Latest blog post {$post.date|format_date:"jS F Y"} | Posted by {$post.firstname} {$post.surname} | <a href="{$post.link}">Comments: {$post.comments}</a></p>
        <h4><a href="{$post.link}">{$post.title}</a></h4>
        <p>{$post.post}</p>
      </div>

      <div class="sideboxbase">
        <h4 class="newseventsbase"><a href="{$post.link}">Read whole post</a></h4>
      </div>
