{section name=news loop=$sidebox_news}
    <li>{$sidebox_news[news].date|format_date:"j/m/y"}&nbsp; <a href="{$sidebox_news[news].link|escape:"html"}">{$sidebox_news[news].title}</a></li>
{sectionelse}
    <li>no news right now... come back soon</li>
{/section}
