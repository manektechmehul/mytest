{if $sidebox_opl}
<div class="associatedlistsmall">
{section name=item loop=$sidebox_opl}
<!--
        {$sidebox_opl[item].title}
        {$sidebox_opl[item].summary}
       {$sidebox_opl[item].link_type}
        {$sidebox_opl[item].module_id}
        {$sidebox_opl[item].file}
        {$sidebox_opl[item].thumb}
       {$sidebox_opl[item].link}
        {$sidebox_opl[item].external_link}
        {$sidebox_opl[item].video_type}
        {$sidebox_opl[item].video_id}
       {$sidebox_opl[item].freetext}
       {$sidebox_opl[item].video_id}
 {$sidebox_opl[item].cs_title}
  {$sidebox_opl[item].cs_desc}
  {$sidebox_opl[item].cs_id}

-->

  
       {if $sidebox_opl[item].link_type == '1'}
            <!-- case study -->
        <div class="downloaditem" style="background-image:url(/images/linksbg-blank.png);">
        
           {if $sidebox_opl[item].cs_thumb != ''}
            <div class="downloadcontent" style="background-image:url({get_thumb_for_background filename=$sidebox_opl[item].cs_thumb size=26});">
            {else}
             <div class="downloadcontent" >           
            {/if}
        
              <h4 class="downloadtitle-case">Case Study</h4>
              <h3>{$sidebox_opl[item].cs_title}</h3>
              <p>{$sidebox_opl[item].cs_desc}<br />
              <a href="/case-studies/{$sidebox_opl[item].cs_id}">Read more</a></p>
            </div>
            <div class="clearfix"></div>
          </div>
       {/if}
       {if $sidebox_opl[item].link_type == '2'}
            <!-- Download -->
           <div class="downloaditem" style="background-image:url(/images/linksbg-file.png);">
           
         
            {if $sidebox_opl[item].thumb != ''}
            <div class="downloadcontent" style="background-image:url({get_thumb_for_background filename=$sidebox_opl[item].thumb size=26});">
            {else}
             <div class="downloadcontent" >           
            {/if}
                    <h4 class="downloadtitle-file">File</h4>
                    <h3>{$sidebox_opl[item].title}</h3>
                    <p>{$sidebox_opl[item].summary}<br />
                        <a target="_blank" href="/UserFiles/File/{$sidebox_opl[item].file}">Download file</a></p>
                </div>
                <div class="clearfix"></div>
            </div>
       {/if}
       {if $sidebox_opl[item].link_type == '3'}
            <!-- Link -->
            <div class="downloaditem" style="background-image:url(/images/linksbg-link.png);">
            {if $sidebox_opl[item].thumb != ''}
            <div class="downloadcontent" style="background-image:url({get_thumb_for_background filename=$sidebox_opl[item].thumb size=26});">
            {else}
             <div class="downloadcontent" >           
            {/if}
                    <h4 class="downloadtitle-link">Link</h4>
                    <h3>{$sidebox_opl[item].title}</h3>
                    <p>{$sidebox_opl[item].summary}<br />
                       {if $sidebox_opl[item].external_link == '1' }
                            <a target="_blank" href="{$sidebox_opl[item].link}">Follow Link</a></p>
                        {else}
                            <a  href="{$sidebox_opl[item].link}">Follow Link</a></p>
                        {/if}
                </div>
                <div class="clearfix"></div>
            </div>
       {/if}
       {if $sidebox_opl[item].link_type == '4'}
            <!-- Video -->
            <div class="downloaditem" style="background-image:url(/images/linksbg-video.png);">
            
             {if $sidebox_opl[item].video_type == "1" }
             		<div class="downloadcontent"   style='background-size: 100px Auto; background-position:  -20px 0px;  background-image:url(http://img.youtube.com/vi/{ $sidebox_opl[item].video_id}/1.jpg);  '  >
             {else}
                    <div class="downloadcontent">
             {/if}
            
           
            
         
                    <h4 class="downloadtitle-video">Video</h4>
                    <h3>{$sidebox_opl[item].title}</h3>
                    <p>{$sidebox_opl[item].summary}<br />
                   {if $sidebox_opl[item].video_type == "1" }
                       <a target="_blank" class="video" href="php/video_handler/youtube.php?height=450&width=800&clip_id={$sidebox_opl[item].video_id}">Watch video</a></p>
                    {/if}
                     {if $sidebox_opl[item].video_type == "2" }
                       <a target="_blank" class="video" href="/php/video_handler/vimeo.php?height=450&width=800&clip_id={$sidebox_opl[item].video_id}">Watch video</a></p>
                    {/if}
               </div>
                <div class="clearfix"></div>
            </div>
        {/if}
       {if $sidebox_opl[item].link_type == '5'}
            <!-- static -->
            
           
            
            <div class="downloaditem" style="background-image:url(/images/linksbg-blank.png);">
            {if $sidebox_opl[item].thumb != ''}
            <div class="downloadcontent" style="background-image:url({get_thumb_for_background filename=$sidebox_opl[item].thumb size=26});">
            {else}
             <div class="downloadcontent" >           
            {/if}
              <h4 class="downloadtitle-profile">Profile</h4>
               <h3>{$sidebox_opl[item].title}</h3>
              <p>{$sidebox_opl[item].freetext}</p>
            </div>
            <div class="clearfix"></div>
          </div>
            
            
            
       {/if}
       <!--
          <div class="downloaditem" style="background-image:url(/images/linksbg-link.png);">
            <div class="downloadcontent">
              <h4 class="downloadtitle-link">Link</h4>
              <h3>Example link</h3>
              <p>Donec id mi odio, non ultrices libero<br />
              <a target="_blank" href="http://www.google.co.uk/">Follow Link</a></p>
            </div>
            <div class="clearfix"></div>
          </div>
         <div class="downloaditem" style="background-image:url(/images/linksbg-file.png);">
            <div class="downloadcontent" style="background-image:url(/images/linksbg-file-generic.png);">
              <h4 class="downloadtitle-file">File</h4>
              <h3>Example download</h3>
              <p>Donec id mi odio, non ultrices libero etiam adipiscing elit<br />
              <a target="_blank" href="#">Download file</a></p>
            </div>
            <div class="clearfix"></div>
          </div>
         <div class="downloaditem" style="background-image:url(/images/linksbg-file.png);">
            <div class="downloadcontent">
              <h4 class="downloadtitle-file">File</h4>
              <h3>Example download</h3>
              <p>Donec id mi odio, non ultrices libero etiam adipiscing elit<br />
              <a target="_blank" href="#">Download file</a></p>
            </div>
            <div class="clearfix"></div>
          </div>
         <div class="downloaditem" style="background-image:url(/images/linksbg-video.png);">
            <div class="downloadcontent">
              <h4 class="downloadtitle-video">Video</h4>
              <h3>Example video</h3>
              <p>Donec id mi odio, non ultrices libero etiam adipiscing elit<br />
              <a target="_blank" href="#">Watch video</a></p>
            </div>
            <div class="clearfix"></div>
          </div>
         <div class="downloaditem" style="background-image:url(/images/linksbg-blank.png);">
            <div class="downloadcontent" style="background-image:url(/images/exampleprofile.jpg);">
              <h4 class="downloadtitle-profile">Profile</h4>
              <h3>Example static profile</h3>
              <p>Donec id mi odio, non ultrices libero etiam adipiscing elit. Donec id mi odio, non ultrices libero. Etiam adipiscing sodales elit, eu venenatis urna non ultrices libero</p>
            </div>
            <div class="clearfix"></div>
          </div>
       -->
   {/section}
</div>
{/if}