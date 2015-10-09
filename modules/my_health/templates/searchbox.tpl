<div id="document-search">
  <div class="docsearchbox">
    <h3>My Health Search</h3> 
    <form action="/{$page_url_parts[0]}/results" method="get" class="document-search">
      <div class="form-row">
          <label class="form-label" for="keywords" >Search for:</label>
          <input type="text" id="keywords" name="keywords" value="{$smarty.get.keywords}"/>
      </div>
      
      {if $hasCategories}
      <div class="form-row">
          <label class="form-label" for="category" >Category:</label>      
          {html_options name=category options=$my_health_searchCategories selected=$smarty.get.category}
      </div>
      {/if}
      
      <div class="form-row" style="margin:10px 0 0;">
          <input type="submit" src="/images/downloadssearch.png" name="search" value="Search Properties" class="searchbutton">
      </div>
    </form>
  </div>
</div>
