{section name=page loop=$lit_review_pages}     
     <!-- left side panel - with book details  --> 		
        <div class='feature softpage index'>    
            <h1>{$lit_review_pages[page].title}</h1>
            <ul class="bookinfo">
               <li><b>Author:</b> {$lit_review_pages[page].author}</li>
               <li><b>Date of Publication:</b> {$lit_review_pages[page].date_of_publication}</li>
               <li><b>Origin:</b> {$lit_review_pages[page].origin}</li>
            </ul>
            <!-- keep links away from outer edges of the book, or the link will just go back one page, rather than to index -->
            <p class="bookindexlink"><a href="#features/2">Back to Index</a></p>
        </div>              
              
     <!--  right side content page -->         
	    <div class='feature softpage'>                    
            <p>{$lit_review_pages[page].body}</p>   
        </div>
 {/section}