
  {* $smarty->assign('doctors', $doctors);
        $smarty->assign('nurses', $nurses);
        $smarty->assign('greenfingers', $greenfingers);
        $smarty->assign('management', $management);
        $smarty->assign('reception', $reception); ~*}
        
<div style="float: left; width: 40%" >        
        
<div class="doctors_list">
<h2> doctors </h2>
{section name=n loop=$doctors}        
   
    <div class="{$listName}left">
        {assign var="alt" value="alt='`$doctors[n].title`'"}
      <a href="/{$page_url_parts[0]}/{$doctors[n].page_name}">{show_thumb filename=$doctors[n].thumb size='200x200' alt=$alt border="0" class="class=left"' }</a>
    </div>
		
    <div class="{$listName}right">	
      <h2>{$doctors[n].title} {$doctors[n].firstname} {$doctors[n].surname}</h2>
      <p>{$doctors[n].description}     
      
      </p>
   </div>
                
    <div class="clearfix"></div>

{sectionelse}
    <p>There are currently no doctors Listed.</p>
{/section}
</div>



<div class="greenfingers_list">
<h2> greenfingers </h2>
{section name=n loop=$greenfingers}        
   
    <div class="{$listName}left">
        {assign var="alt" value="alt='`$greenfingers[n].title`'"}
      <a href="/{$page_url_parts[0]}/{$greenfingers[n].page_name}">{show_thumb filename=$greenfingers[n].thumb size='200x200' alt=$alt border="0" class="class=left"' }</a>
    </div>
		
    <div class="{$listName}right">	
      <h2>{$greenfingers[n].title} {$greenfingers[n].firstname} {$greenfingers[n].surname}</h2>
      <p>{$greenfingers[n].description}     
      
      </p>
   </div>
                
    <div class="clearfix"></div>

{sectionelse}
    <p>There are currently no greenfingers Listed.</p>
{/section}
</div>






<!-- end first col -->
</div>
<div style="float: right; width: 60%" >  





        
<div class="nurse_list">
<h2> Nurses </h2>
{section name=n loop=$nurses}        
   
    <div class="{$listName}left">
        {assign var="alt" value="alt='`$nurses[n].title`'"}
      <a href="/{$page_url_parts[0]}/{$nurses[n].page_name}">{show_thumb filename=$nurses[n].thumb size='200x200' alt=$alt border="0" class="class=left"' }</a>
    </div>
		
    <div class="{$listName}right">	
      <h2>{$nurses[n].title} {$nurses[n].firstname} {$nurses[n].surname}</h2>
      <p>{$nurses[n].description}     
      
      </p>
   </div>
                
    <div class="clearfix"></div>

{sectionelse}
    <p>There are currently no nurses Listed.</p>
{/section}
</div>




<div class="managements_list">
<h2> managements </h2>
{section name=n loop=$managements}        
   
    <div class="{$listName}left">
        {assign var="alt" value="alt='`$managements[n].title`'"}
      <a href="/{$page_url_parts[0]}/{$managements[n].page_name}">{show_thumb filename=$managements[n].thumb size='200x200' alt=$alt border="0" class="class=left"' }</a>
    </div>
		
    <div class="{$listName}right">	
      <h2>{$managements[n].title} {$managements[n].firstname} {$managements[n].surname}</h2>
      <p>{$managements[n].description}     
      
      </p>
   </div>
                
    <div class="clearfix"></div>

{sectionelse}
    <p>There are currently no managements Listed.</p>
{/section}
</div>


<div class="receptions_list">
<h2> receptions </h2>
{section name=n loop=$receptions}        
   
    <div class="{$listName}left">
        {assign var="alt" value="alt='`$receptions[n].title`'"}
      <a href="/{$page_url_parts[0]}/{$receptions[n].page_name}">{show_thumb filename=$receptions[n].thumb size='200x200' alt=$alt border="0" class="class=left"' }</a>
    </div>
		
    <div class="{$listName}right">	
      <h2>{$receptions[n].title} {$receptions[n].firstname} {$receptions[n].surname}</h2>
      <p>{$receptions[n].description}     
      
      </p>
   </div>
                
    <div class="clearfix"></div>

{sectionelse}
    <p>There are currently no receptions Listed.</p>
{/section}
</div>








<!--  end second col -->
</div>