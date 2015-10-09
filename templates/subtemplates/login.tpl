{* this is the login sidebox template                                                    *}
{* There are two states logged in or not logged in                                       *}
<div id="toppanel">
	
  <div id="loginpanel">
  {if $logged_in}
  {else}
    <div id="loginpanelinside">
      <div class="memberloginintro">
        <h3>Interactive discussion board</h3>
        <p>Use this to engage with others around the country who have an interest in the different aspects of church growth. We really value your opinions and experiences and we would love for you to be a part of this exciting research programme.</p>
      </div>
      <div class="memberlogin">
        <h3 style="padding-bottom:2px;">Do you already have an account?</h3>
        <form id="form1" name="form1" method="post" action="/discussions">
          <span class="membertext">email:</span>
          <input class="form-field" type="text" name="email" />
          <span class="membertext">Password:</span>
            <input class="form-field" type="password" name="password" />
            <input type="hidden" name="login" id="login" value="login"/>
          <input type="image" name="Submit" value="Submit" src="/images/login.png" width="300" height="40" />
        </form>
        <p style="padding-left:5px;"><a href="/forgot_password">Reset your password</a></p>
      </div>
      <div class="membersignup">
        <h3>Need to sign up?</h3>
        <p style="margin-top:10px;"><a href="/sign_up"><img border="0" src="/images/getaccount.png" width="180" height="80" alt="Get your free account now" /></a></p>
      </div>
    </div>
  {/if}
  </div>

  <div id="tab-container">
	<div class="tab">  
      <div id="login">
        {if $logged_in}
        <div class="welcome">Welcome {$membername}</div>  
        <div class="loggedin">
            <a href="/discussions">Discuss</a> | <a href="/logout">Logout</a>		
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