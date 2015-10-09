<script>
    
<?
if(basename($_SERVER["REQUEST_URI"], ".php") != 'index'){
 ?>
    
    
$(function() {
page_lifetime=<? 


echo ini_get("session.gc_maxlifetime");
 
?>; 
// Our countdown plugin takes a callback, a duration, and an optional message
$.fn.countdown = function (callback, duration, message) {
    // If no message is provided, we use an empty string
    message = message || "";
    // Get reference to container, and set initial content
    var container = $(this[0]).html(duration + message);
    // Get reference to the interval doing the countdown
    var countdown = setInterval(function () {
        // If seconds remain
    if (--duration) {
            // Update our container's message
	s = duration;			
	var h = Math.floor(s/3600); //Get whole hours
    s -= h*3600;
    var m = Math.floor(s/60); //Get remaining minutes
    s -= m*60;
    formatted_duration =  h+":"+(m < 10 ? '0'+m : m)+":"+(s < 10 ? '0'+s : s);	
    container.html(formatted_duration + message);
        // Otherwise
        } else {
            // Clear the countdown interval
            clearInterval(countdown);
            // And fire the callback passing our container as `this`
            callback.call(container);   
        }
    // Run interval every 1000ms (1 second)	
	if(duration > 1 && duration < 120){
		 $(".countdown").css({ "background-color": "#666", "color": "#fff",  "font-size": "3em" });	
	}	
    }, 1000);

};
// Use p.countdown as container, pass redirect, duration, and optional message
// page_lifetime
$(".countdown").countdown(redirect, page_lifetime , " Session time remaining");
// Function to be called after 5 seconds
function redirect () {
    this.html("Session is now expired.");
    this.css({"background-color": "#f00","color": "#fff","font-size": "5em"});
}

});


<? } ?>
</script>
<p class="countdown" style="text-size: 2em; position: fixed; top: 0px; right: 0px; background-color: #fff; clear:none; padding: 5px; margin: 5px;"></p>
<img src="/images/whitelogo.gif" hspace=10>
<p>
 
    <?php
    $base_path = $_SERVER['DOCUMENT_ROOT'];
    if (!empty($session_user_data) && is_array($session_user_data)) {
        $eventsAccess = $session_user_data['events_access'];
        $newsAccess = $session_user_data['news_access'];
    }
    include_once ("$base_path/php/javascript_rollover_inc.php");

    function show_nav_tab_image($link, $name, $text, $image) {
        global $admin_tab;
        $state = 'off';
        if ($admin_tab == $name)
            $state = 'over';
        echo "<a href=\"$link\" onmouseout=\"MM_swapImgRestore()\" onmouseover=\"MM_swapImage('$name','','/admin/images/$image-over.gif',0)\">";
        echo "<img src=\"/admin/images/$image-$state.gif\" alt=\"$text\" name=\"$name\" border=\"0\" class='header-tab'/></a>";
    }

    function show_nav_tab($link, $name, $text) {
        global $admin_tab;
        $btn_txt = str_replace(' ', '_', $text);
        $state = 'off';
        if ($admin_tab == $name)
            $state = 'over';
        echo "<a href=\"$link\" onmouseout=\"MM_swapImgRestore()\" onmouseover=\"MM_swapImage('$name','','/admin/images/buttons/navtab-{$btn_txt}-over.gif',0)\">";
        echo "<img src=\"/admin/images/buttons/navtab-{$btn_txt}-$state.gif\" alt=\"$text\" name=\"$name\"  hspace=\"3\" border=\"0\" /></a>";
    }
    ?>


<div id="adminbox">
    <div class="header">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td>
                    <?php 
//echo '<img src="/admin/images/header-logo.gif" alt="Creative Stream" width="92" height="40" border="0" />';
                    ?>
                </td>
                <td valign="bottom"><div align="right">
                        <?php
                        if (($session_user_id == "1") || ($session_user_type_id == "1")) {
                            show_nav_tab_image("/admin/index.php", 'settings', 'Settings', 'nav-settings');
                        }

                        if ($session_user_id == "1")
                            show_nav_tab_image("/modules/forms/admin/forms.php", 'forms_admin', 'Forms', 'nav-forms');


                        if ($session_user_type_id == "1")
                            show_nav_tab_image("/admin/user.php?action=list&search_by=all", 'user_admin', 'Users', 'nav-users');

                        if (($session_user_type_id == "1") || ($session_access_to_cms))
                            show_nav_tab_image("/admin/content_admin.php", 'content_admin', 'Content Admin', 'nav-content');

                        if (($session_user_type_id == "1") || ($eventsAccess == 1))
                            if (SITE_HAS_EVENTS == "1")
                                show_nav_tab_image("/modules/events/admin/events.php?content_type_id=4", 'events', 'Events', 'nav-events');
                        if (($session_user_type_id == "1") || ($newsAccess == 1))
                            if (SITE_HAS_NEWS == "1")
                                //?content_type_id=3
                                show_nav_tab_image("/modules/news/admin/news.php", 'news', 'News', 'nav-news');

                               
                        if ($session_user_type_id == "1") {
                            if (SITE_HAS_CASE_STUDIES == "1")
                                show_nav_tab_image("/modules/case_studies/admin/main.php", 'case_studies', 'Case Studies', 'nav-casestudies');
                            if (SITE_HAS_FAQ == "1")
                                show_nav_tab_image("/modules/faq/admin/main.php", 'faq', 'Faqs', 'nav-casestudies');
                            if (SITE_HAS_MEMBERS == "1")
                                show_nav_tab_image("/modules/members/admin/member_pages.php", 'member_pages', 'Member Pages', 'nav-membercontent');
                            if (SITE_HAS_DOCUMENTS == "1")
                                show_nav_tab_image("/modules/documents/admin/documents.php", 'documents', 'Documents', 'nav-documents');
                            if (SITE_HAS_LITREVIEW == "1")
                                show_nav_tab_image("/modules/litreview/admin/lit_review.php", 'lit_review_admin', 'Literature Review', 'nav-litreview');
                            if (SITE_HAS_MEMBERS == "1")
                                show_nav_tab_image("/modules/members/admin/members.php", 'member_admin', 'Member', 'nav-members');
                            if (SITE_HAS_NOTICEBOARD == "1")
                                show_nav_tab_image("/modules/noticeboard/admin/notice_categories.php", 'noticeboard_admin', 'Discussions', 'nav-discussions');
                            if (SITE_HAS_LINKS == "1")
                                show_nav_tab_image("/modules/links/admin/links.php", 'links', 'Promos', 'nav-promos');
                            if (SITE_HAS_MESSAGE_BLOCKS == "1")
                                show_nav_tab_image("/modules/message_blocks/admin/main.php", 'message_blocks', 'Message_blocks', 'nav-message-blocks');
                            if (SITE_HAS_VIDEO == "1") {
                                show_nav_tab_image("/modules/video/admin/main.php", 'video', 'video', 'nav-video');
                            }
                            if (SITE_HAS_PROPERTY == "1") {
                                show_nav_tab_image("/modules/property/admin/main.php", 'property', 'property', 'nav-properties');
                            }
                            if (SITE_HAS_DIRECTORY == "1") {
                                show_nav_tab_image("/modules/directory/admin/main.php", 'directory', 'directory', 'nav-directory');
                            }
                            if (SITE_HAS_POLLS == "1") {
                                show_nav_tab_image("/modules/polls/admin/polls.php", 'polls', 'polls', 'nav-polls');
                            }
                            
                            
                              if (SITE_HAS_PROFILES == "1") {
                                show_nav_tab_image("/modules/profiles/admin/main.php", 'profile', 'profile', 'nav-profiles');
                            }

	                        if (SITE_HAS_MAP == "1") {
		                        show_nav_tab_image("/modules/map/admin/main.php", 'map', 'map', 'nav-map');
	                        }
	
  if (SITE_HAS_MEMORYBOOK == "1") {
                                show_nav_tab_image("/modules/memorybook/admin/main.php", 'memorybook', 'memorybook', 'nav-memorybook');
                            }

	                        if (SITE_HAS_DONATE == "1") {
		                        show_nav_tab_image("/modules/donate/admin/main.php", 'donate', 'donate', 'nav-donate');
	                        }


 if (SITE_HAS_BOOKING == "1") {
                                show_nav_tab_image("/modules/booking/admin/main.php", 'booking', 'booking', 'nav-booking');
                            }
							 if (SITE_HAS_LOTTERY == "1") {
                                show_nav_tab_image("/modules/lottery/admin/main.php", 'lottery', 'lottery', 'nav-lottery');
                            }

	                        if (SITE_HAS_MY_HEALTH == "1") {
                                show_nav_tab_image("/modules/my_health/admin/main.php", 'my_health', 'my_health', 'nav-my_health');
                            }
	                        if (SITE_HAS_NEWSLETTER == "1") {
		                        show_nav_tab_image("/modules/newsletter/admin/main.php", 'newsletter', 'newsletter', 'nav-newsletter');
	                        }
                            
                        }

                        if (($session_user_type_id == "1") || ($session_access_to_cms)) {
                            if (SITE_HAS_GALLERY == "1")
                                show_nav_tab_image("/modules/gallery/admin/galleries.php", 'gallery_admin', 'Gallery', 'nav-gallery');
                        }
                        if (($session_user_type_id == "1") || ($session_access_to_cms)) {
                            if (SITE_HAS_BLOG == "1")
                                show_nav_tab_image("/modules/blog/admin/blogs.php", 'blog_admin', 'Blog', 'nav-blogs');
                        }


                        if ($session_user_type_id == "1") {
                            if (SITE_HAS_FORUM == "1")
                                show_nav_tab_image("/phorum/admin.php", 'phorum', 'Phorum', 'nav-forum');
                            if (SITE_HAS_SHOP == "1")
                                show_nav_tab_image("/modules/shop/admin/product.php", 'shop', 'Shop', 'nav-shop');
                            if (SITE_HAS_MAILING == "1")
                                show_nav_tab_image("/admin/registrants.php", 'subscriber_admin', 'Subscribers', 'nav-subscribers');
                        }

//if (($session_user_type_id == "1") || ($session_access_to_cms)) {
//	show_nav_tab_image("/admin/help.php", 'help', 'Help', 'nav-help');
//}

                        show_nav_tab_image("/admin/logout.php", 'logout', 'Logout', 'nav-logout');
                        ?>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="underheader"></div>
    <?php
    if (isset($second_level_navigavtion)) {
        echo "<div id=\"second_level_nav\">$second_level_navigavtion<br clear='all'></div>";
    }