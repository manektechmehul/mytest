{* this is the login sidebox template                                                    *}
{* The data is supplied by php/sideboxes/loginbox.php                                    *}
{*                                                                                       *}
{* There are two states logged in or not logged in                                       *}
{* If we are logged in show the menu (only one level - subnav - but treated as main)     *}
           
<div id="toppanel">
	
  <div id="loginpanel">
    <div id="memberlogin">
      {if $logged_in}
      {else}
      <p>Please login below</p>
      <form id="form1" name="form1" method="post" action="/members">
        <span class="membertext">Username</span>
        <input class="form-field" type="text" name="membername" />
        <div class="membergap"></div>
        <span class="membertext">Password</span>
          <input class="form-field" type="password" name="password" />
        <div class="membergap"></div>
          <a href="/forgotten_password">Forgotten password?</a>
          <input type="hidden" name="login" id="login" value="login"/>
        <input type="image" name="Submit" value="Submit" src="/images/button.gif" width="58" height="26" />
      </form>
      {/if}
    </div>
  </div>

  <div id="tab-container">
	<div class="tab">  
      <div id="login">
        {if $logged_in}
        <div class="welcome">Welcome {$membername}</div>  
        <div class="register">
            <a class="simplelinks" href="/discussions" style="float:left;">Discuss</a> | <a href="/logout" style="float:left;">Logout</a>		
        </div> 
        {else}
        <div class="welcome">Welcome Guest</div>  
        <div class="register">              
            <div id="toggle">
                <a id="open" class="open register" href="#" style="float:left;">Login | Register</a>
                <a id="close" style="display:none;" class="close" href="#">Close Panel</a>			
            </div>
        </div> 
        {/if}
      </div>
	</div> 
  </div>
  
</div>