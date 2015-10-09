
            <div class="shopsearchcontainer">
              <p>Events Search</p>
              <form action="/{$page_url_parts[0]}/results" method="get" id="shopsearch" class="document-search">
                <input type="hidden" name="ie" value="UTF-8" />
                <input id="keywords" name="keywords" type="text" class="shopsearch" value="{$smarty.get.keywords}" hspace="0" vspace="0" border="0" /> 
                
                {if $hasCategories}
                <div class="form-row">
                    <label class="form-label" for="category" >Category:</label>      
                    {html_options name=category options=$booking_searchCategories selected=$smarty.get.category}
                </div>
                {/if}
                
                <input value="GO" id="submit-search" name="submit-search" type="submit" class="shopsearchbutton csbutton" hspace="0" vspace="0" border="0" align="top" />
              </form>
              <div class="clearfix"></div>
            </div>