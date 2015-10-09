{* This is the large search that displays on the MODULE LANDING PAGE *}
<div class="memorybookmain">
    <div class="memorybooksearch">
        <form action="/memory-book/results" method="get" class="document-search">
            <input type="text" class="form-control memorybookfield" onfocus="clearField(this);" value="Search for a loved one" name="keywords" id="keywords" />
            <input type="submit" class="form-btn memorybookbutton" value="Search &gt;" title="Search" name="search" />
        </form>
        <div class="memorybookcreatenew"><a href="/add-to-memory-book">Create a new page</a></div>
    </div>
</div>