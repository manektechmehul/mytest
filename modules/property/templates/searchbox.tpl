{literal}
<style>
    .docsearchbox{
        width:100%;
    }
    .docsearchbox .form-row select{
        min-width: 30%;
        padding: 4px 6px;
        border: 1px solid #aaa;
        font-size: 100%;
        margin: 0 17px 20px;
        vertical-align: baseline;
        float: left;        
    }    
</style>
<script src="/modules/property/js/ajax.js"></script>     
{/literal}


<div id="--document-search">
  <div class="docsearchbox">    
    <form action="/{$page_url_parts[0]}/results" method="post" class="search">
   <!--    <div class="form-row">
          <label class="form-label" for="keywords" >Search for:</label>
          <input type="text" id="keywords" name="keywords" value="{$smarty.get.keywords}"/>
      </div>
     -->      
     <!-- <div class="form-row">
          <label class="form-label" for="type" >Type:</label>
          {html_options name=type options=$property_searchType selected=$smarty.get.type}
      </div>
      <div class="form-row">
          <label class="form-label" for="status" >Status:</label>
          {html_options name=status options=$property_searchStatus selected=$smarty.get.status}
      </div>      -->
      <div class="form-row">
          {html_options name=bedroom options=$property_searchBedroom selected=$smarty.post.bedroom id=pr_bedroom}
      </div> 
      <div class="form-row">     
          {html_options name=location options=$property_searchLocation selected=$smarty.post.location id=pr_location}
      </div>  
      <div class="form-row" style="float:none;">
          {html_options name=year options=$property_searchYear selected=$smarty.post.year id=pr_year}
      </div>       
    </form>
  </div>
</div>
