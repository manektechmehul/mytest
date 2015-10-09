<div class='feature pagefx softpage index'>
<img src="/images/indextitle.png" width="60" height="14" alt="Index" style="padding-bottom:10px;" />       
<h1>{$current_cat_title}</h1>	
<ul class="index">        
{foreach from=$lit_review_index key=i item=review name=docs }
        {if $smarty.foreach.docs.index < 10}
                <li><a href="#features/{$review.page_no}">{$review.title} &nbsp;
                <span class="author">{$review.author}</span></a></li>
         {/if}
    {/foreach}
</ul>                 
</div>    
<div class='feature pagefx index'>     
<ul class="index">         
	{foreach from=$lit_review_index key=i item=review name=docs }
        {if $smarty.foreach.docs.index > 9} 
                <li><a href="#features/{$review.page_no}">{$review.title}  &nbsp;
                <span class="author">{$review.author}</span></a></li>
         {/if}
    {/foreach}
</ul>
</div>  