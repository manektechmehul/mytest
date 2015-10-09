<form action='/shop/results' method="post" id='detail-search'>
    <div>
        <label for='keywords'>Search for</label> <input type='text' id='keywords' name='search_keywords' title='Words to search for' />
    </div>
    <div>
        <label for='category_id'>Category</label>
        <select name='category_id'  id='category_id'>
            {html_options options=$categories}
        </select>
    </div>
    <input type='submit' id='submit-detailed-search' name='submit-detailed-search' value='search'>
</form>