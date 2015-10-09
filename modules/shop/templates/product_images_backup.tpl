<div id="gallery">
    <div id="slides"> {section name=item loop=$thumbs}
        <div class="slide">{show_thumb filename=$thumbs[item] crop=crop size=281x281 alt="alt=\"Slide\" width=281 height=281"}</div>
        {/section} </div>
        <div id="gallery_menu">
            <ul>
                {section name=item loop=$thumbs}
                    <li class="menuItem" ><a href="">{show_thumb filename=$thumbs[item] crop=crop size=64x64 alt="alt=\"thumbnail\" width=64 height=64"}</a></li>
                    {/section}
            </ul>
        </div>
    </div>