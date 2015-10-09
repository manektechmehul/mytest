{if $gallery.image_id}
{$gallery.image.thumb}<br />
<span class="latest-title">{$gallery.image.title}</span>
<span class="latest-text">{$gallery.image.description}</span><br />
<table class="big-image-table" width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="16"><img src="/images/icon-size.gif" alt="size" /></td>
        <td class="latest-text">{$gallery.image.size}</td>

      </tr>
      <tr>
        <td><img src="/images/icon-price.gif" alt="price"  /></td>
        <td class="latest-text">{$gallery.image.price}</td>
      </tr>
    </table> 

<table width="100%" border="0" cellspacing="0" cellpadding="0" id="nav-arrows">
  <tr align="center">
    <td id="nav-arrows">
    	{if $gallery.image.previd}
    	<a href="{$gallery.self}?image={$gallery.image.previd}" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('leftarrow','','/images/arrow-left-over.gif',1)">
    		<img src="/images/arrow-left-off.gif" alt="previous print" name="leftarrow" width="28" height="28" border="0" class="nav-arrow" id="leftarrow" />
    	</a> 
    	{else}
    		<img src="/images/arrow_left_dis.png" alt="previous print" name="leftarrow" width="28" height="28" border="0" class="nav-arrow" id="leftarrow" />
    	{/if}

    	{if $gallery.image.nextid}
    	<a href="{$gallery.self}?image={$gallery.image.nextid}" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('rightarrow','','/images/arrow-right-over.gif',1)">
    		<img src="/images/arrow-right-off.gif" alt="next print" name="rightarrow" width="28" height="28" border="0" class="nav-arrow" id="rightarrow" />
    	</a>
    	{else}
    		<img src="/images/arrow_right_dis.png" alt="next print" name="rightarrow" width="28" height="28" border="0" class="nav-arrow" id="rightarrow" />
    	{/if}
    	</td>
  </tr>
  <tr align="center">
  <td>   <br>
    	<a href="{$gallery.self}?page={$gallery.page}"><img src="/images/backtoportfolio.gif" alt="Back to Gallery" name="backtoportfolio"  border="0"/>
    	</a>
  </td>
  </tr>
</table>
    
{else}
<span class="latest-title">{$gallery.title}</span>
<!-- CONTENT STARTS HERE -->
{section name=preimageloop loop=$gallery.previous_pages_images}
     {$gallery.previous_pages_images[preimageloop]}
{/section}
{section name=imageloop loop=$gallery.image_grid_rows}
     {$gallery.image_grid_rows[imageloop]}
{/section}
{section name=postimageloop loop=$gallery.next_pages_images}
     {$gallery.next_pages_images[postimageloop]}
{/section}

{if ($gallery.gallery_id)}
	<div id="gallery-base">


    	{if $gallery.page > 1}
			<a href="{$gallery.self}?gallery_id={$gallery.gallery_id}&page={$gallery.prevpage}" class="gallery-base-link">Previous</a>
    	{else}
    		<span class="link-inactive">Previous</span>
    	{/if}

		<span class="link-spacer">|</span> 
		
		{section name=pageloop start=1 loop=$gallery.pages+1}
			{if ($smarty.section.pageloop.index == $gallery.page) }
			<a href="{$gallery.self}?gallery_id={$gallery.gallery_id}&page={$smarty.section.pageloop.index}" class="gallery-base-link-on">{$smarty.section.pageloop.index}</a>
			{else}
			<a href="{$gallery.self}?gallery_id={$gallery.gallery_id}&page={$smarty.section.pageloop.index}" class="gallery-base-link">{$smarty.section.pageloop.index}</a>
			{/if}
		{/section}
		
		<span class="link-spacer">|</span> 

    	{if $gallery.page < $gallery.pages}
			<a href="{$gallery.self}?gallery_id={$gallery.gallery_id}&page={$gallery.nextpage}" class="gallery-base-link">Next</a>
    	{else}
    		<span class="link-inactive">Next</span>
    	{/if}
		<br/>
		<a href="{$gallery.self}">Back to Gallery List</a>
	</div>
{/if}  
{/if}  
