<div class="shopgallery">
    {section name=item}     
      {if ($thumbs[item][0] == '1' && $thumbs[item][1] != '' && $thumbs[item][2] != '' )   || ($thumbs[item][0] == '0' && $thumbs[item][1] != '')}    
        <div class="cloudzoom-holder" >
            {if $thumbs[item][0] == '1'}
                {* preserve the image as its probably transparent  - [1] is large [2] is small *}
                <img class="cloudzoom"  src="{$thumbs[item][2]}" data-cloudzoom="tintOpacity:0, zoomImage:'{$thumbs[item][1]}', zoomPosition:'inside', autoInside:true, tintColor:'#E3E3E3'" />       
            {else}
                <img class="cloudzoom" src="{show_thumb_minimal filename=$thumbs[item][1] size=600x600}" data-cloudzoom="zoomImage:'{show_thumb_minimal filename=$thumbs[item][1] size=1000x1000}', zoomPosition:'inside', autoInside:true, tintOpacity:0" />
            {/if}
        </div>
    {/if}  
    
    {/section}
    <div class="shopgallerymenu">
         {section name=item loop=$thumbs}        
         {if ($thumbs[item][0] == '1' && $thumbs[item][1] != '' && $thumbs[item][2] != '' )   || ($thumbs[item][0] == '0' && $thumbs[item][1] != '')}        
            <div class="cloudzoom-gallery-holder">
                {if $thumbs[item][0] == '1'}
                    <img class="cloudzoom-gallery" src="{$thumbs[item][2]}" data-cloudzoom="useZoom:'.cloudzoom', image:'{$thumbs[item][2]}', zoomImage:'{$thumbs[item][1]}'" />
                {else}    
                    <img class="cloudzoom-gallery" src="{show_thumb_minimal filename=$thumbs[item][1] size=150x150}" data-cloudzoom="useZoom:'.cloudzoom', image:'{show_thumb_minimal filename=$thumbs[item][1] size=600x600}', zoomImage:'{show_thumb_minimal filename=$thumbs[item][1] size=1000x1000}'" />
                {/if}
            </div>
            {/if}
        {/section}
    </div>

    <div class="clearfix"></div>
</div>
