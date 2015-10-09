<div id="document-search">
  <div class="docsearchbox">
    <h3>Downloads Search</h3>
    <form action="{$member_module_root}/results" method="post" class="document-search">
      <div class="form-row">
          <label class="form-label" for="keywords" >Keywords:</label>
          <input type="text" id="keywords" name="keywords" value="{$keywords}"/>
      </div>
      <div class="form-row">
          <label class="form-label" for="category" >Category:</label>
          <select id="category" name="category">
              <option value="0">All</option>
              {foreach from=$searchCategories item=cat}
                  <option value="{$cat.id}">{$cat.title}</option>
              {/foreach}
              </select>
      </div>
      <div class="form-row" style="margin:10px 0 0;">
          <input type="submit" src="/images/downloadssearch.png" name="search" value="Search Documents" class="searchbutton">
      </div>
    </form>
  </div>
</div>