<div id="socialmediafloat">
    {googlep link="`$site_address``$pageName`" image="/images/share-single-g.gif" class="socialmedia"}
    {linkedin link="`$site_address``$pageName`" image="/images/share-single-li.gif" class="socialmedia"}
    {twitter link="`$site_address``$pageName`" image="/images/share-single-tw.gif" class="socialmedia"}
    {facebook link="`$site_address``$pageName`" text=$singleitem.title image="/images/share-single-fb.gif" class="socialmedia"}
</div>
<div class="{$listName}singleitem">

    <h3> Stats </h3>
    {show_thumb filename=$singleitem.thumb size='300x300' alt='alt="'$singleitem.title'" class="right" border="0"'} 
    
    
     {$singleitem.title} {$singleitem.firstname} {$singleitem.surname} <br>
       
           {$singleitem.phone} <br>
           {$singleitem.email} <br>
        
           
            <a target="_blank" href="{check_link link=`$singleitem.website_social_link` external=`1`}">{$singleitem.website_social_link}</a> <br>
             
           {$singleitem.gender}<br>
           {$singleitem.age_group}<br>
           
           
           {$singleitem.specialist_topic} <br>
           {$singleitem.organisation}   <br>
           {$singleitem.organisation_position} <br>
           
           
              

	<h3> Bio </h3>
		
		
	
    
    
    {$singleitem.description}





</div>
<p class="csbutton"><a {if $button_link == "do_js_back"} onclick="javascript:history.back(-1);" href="#" {else}  href='{$button_link}' {/if} >{$button_text}</a>
</p>

