 


<div id="debug_footer" style="margin-top: 10 px;border-top: 2px solid #468; background: #fff;" >


<h2> Debug Data </h2>

<table border="1"  style="border-top: 2px solid #fff; background: #fff;"  width="100%" >
<tr>
<td align=left valign=top width= 50%>

    
<h2> Constants </h2>
<pre>
<? $all_const = get_defined_constants(true); 
var_dump($all_const['user']);
?>
</pre>    
    
    
    
    
<?php  var_dump($_SESSION['basket']); ?>

<h2> Session Vars </h2>
	<?   
	
	$helper=array_keys($_SESSION);
	
	echo '<ul>';
	
	
	
	
	foreach ($helper as $key){		
		echo '<li>' .  $key . ' ->'  .  $_SESSION[$key] . '</li>' ;
	}
	
	echo '</ul>';
	
        echo '<br>  Var Dump of the Session member Details <br>';
        var_dump($_SESSION['session_member_details']);
?>




</td>
<td valign=top align=left>
<h2> Post Vars </h2>
	<?   
	
	$helper=array_keys($_POST);
	
	echo '<ul>';
	foreach ($helper as $key){
		
		echo '<li>' .  $key . ' ->'  .  $_POST[$key] . '</li>' ;
	}
	echo '</ul>';
	
?> 


<hr>

<h2> Get Vars </h2>
	<?   
	
	$helper=array_keys($_GET);
	
	echo '<ul>';
	foreach ($helper as $key){
		
		echo '<li>' .  $key . ' ->'  .  $_GET[$key] . '</li>' ;
	}
	echo '</ul>';
	
?> 



<hr>

<h2> Cookie Vars </h2>
	<?   
	
	$helper=array_keys($_COOKIE);
	
	echo '<div style=\'width:500px; overflow: scroll; height: 150px; \' ><ul>';
	foreach ($helper as $key){
		
		echo '<li>' .  $key . ' ->'  .  $_COOKIE[$key] . '</li>' ;
	}
	echo '</ul></div>';
	
?> 


<hr>

<h2> SERVER Vars </h2>
	<?   
	
	$helper=array_keys($_SERVER);
	
	echo '<div style=\'width:500px; overflow: scroll; height: 600px; \' > <ul   >';
	foreach ($helper as $key){
		
		echo '<li>' .  $key . ' ->'  .  $_SERVER[$key] . '</li>' ;
	}
	echo '</ul></div>';
	
?> 

 </td>
</tr>


</table>



</div>