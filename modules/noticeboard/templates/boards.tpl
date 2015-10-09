<h3>Our current discussions</h3>
<ul>
{section name=board loop=$boards}
    <li><a href="{$noticeBoardBasePage}/{$boards[board].page_name}">{$boards[board].title}</a></li>
{/section}
</ul>
