<?php

function format_output($output) {
/****************************************************************************
* Takes a raw string ($output) and formats it for output using a special
* stripped down markup that is similar to HTML
****************************************************************************/

    $output = htmlspecialchars(stripslashes($output));

    /* new paragraph */
    $output = str_replace('[p]', '<p>', $output);

  
    /* line breaks */
    $output = str_replace('[br]', '<br>', $output);

    /* underlines */
    $output = str_replace('[u]', '<u>', $output);
    $output = str_replace('[/u]', '</u>', $output);

    /* ordered list */
    $output = str_replace('[ol]', '<ol>', $output);
    $output = str_replace('[/ol]', '</ol>', $output);

    /* unordered list */
    $output = str_replace('[ul]', '<ul>', $output);
    $output = str_replace('[/ul]', '</ul>', $output);

    /* list items */
    $output = str_replace('[li]', '<li>', $output);
    $output = str_replace('[/li]', '</li>', $output);

    /* bold */
    $output = str_replace('[b]', '<b>', $output);
    $output = str_replace('[/b]', '</b>', $output);

    /* italics */
    $output = str_replace('[i]', '<i>', $output);
    $output = str_replace('[/i]', '</i>', $output);

    /* preformatted */
    $output = str_replace('[pre]', '<pre>', $output);
    $output = str_replace('[/pre]', '</pre>', $output);

    /* indented blocks (blockquote) */
    $output = str_replace('[indent]', '<blockquote>', $output);
    $output = str_replace('[/indent]', '</blockquote>', $output);

    /* anchors */
    $output = ereg_replace('\[anchor=&amp;quot;([[:graph:]]+)&amp;quot;\]', '&lt;a name="\1">&lt;/a>', $output);
    

    /* links, note we try to prevent javascript in links */
    $output = str_replace('[link=&amp;quot;javascript', '[link=&amp;quot; javascript', $output);

    /* normal href link */
    $output = ereg_replace('\[link=&amp;quot;([[:graph:]]+)&amp;quot;\]', '<a href="\1"><b>', $output);    
    $output = ereg_replace('\[link=&quot;([[:graph:]]+)&quot;\]', '<a href="\1"><b>', $output);
      
    /* href link to other web site - ie target=_blank*/
    $output = ereg_replace('\[link_to_other_site=&amp;quot;([[:graph:]]+)&amp;quot;\]', '<a href="\1" target=_blank><b>', $output);    
    $output = ereg_replace('\[link_to_other_site=&quot;([[:graph:]]+)&quot;\]', '<a href="\1" target=_blank><b>', $output);

    $output = str_replace('[/link]', '</b></a>', $output);      



    /* mailto link */
    $output = ereg_replace('\[email=&amp;quot;([[:graph:]]+)&amp;quot;\]', '<a href="mailto:\1"><b>', $output);    
    $output = ereg_replace('\[email=&quot;([[:graph:]]+)&quot;\]', '<a href="mailto:\1"><b>', $output);
   
    $output = str_replace('[/email]', '</b></a>', $output);   

   

    return nl2br($output);
}

?> 

