<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<link rel="stylesheet" href="/resources/demos/style.css">
<script>
 $(function() {
	 
	 
	$("#export_csv" ).click(function() {
		$('#filterform').attr('action', 'make_orders_csv.php');
		//$('#filterform').attr('target','_blank');
		$('#filterform').submit();
		return false;
	});
 
 
	$("#submit_filter_orders" ).click(function() {
		$('#filterform').attr('action', '');
		//$('#filterform').attr('target','self');
		$('#filterform').submit();
		return true;
	});
	
	$( "#start_date" ).datepicker({
		changeMonth: true,
		changeYear: true
	});
		
	$( "#end_date" ).datepicker({
		changeMonth: true,
		changeYear: true
	});


	 var payment_type_val = <?
if(isset($_GET['payment_type'])){
	 echo  $_GET['payment_type'];
}else{
	 echo '-1'; //default to all
}
?>;
	 $("#payment_type").val(payment_type_val);





	

var status_val = <?
if(isset($_GET['status'])){
	 echo  $_GET['status'];
}else{
	 echo '-1'; //default to all
}
?>;
$("#status").val(status_val);

var order_by_val = <?
if(isset($_GET['order_by'])){
	 echo  $_GET['order_by'];
}else{
	 echo '-1'; //default to all
}
?>;
$("#order_by").val(order_by_val);
}); // end of on page ready 




function clearfield(field_id){
	$('#' + field_id).val('');	
}



function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=[];
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=[]; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}
</script>
 
<form id="filterform"> 
<style>
#top-row { background:none; }
</style>
<table width="100%" class="adminfiltertable">
  <tr>
    <td class="adminfilter-right">Status</td>
    <td align="left"><select name="status"  id="status" >
      <option value="-1"> All (less incompletes) </option>
      <option value="0"> Incomplete </option>
      <option value="1"> Order Created (Payment accepted) </option>
      <option value="2"> Picking  </option>
      <option value="4"> Dispatched / Complete  </option>
      <option value="6"> On Hold </option>
      <option value="7"> Payment Failed </option>
    </select></td>





	  <td class="adminfilter-right">Payment Type</td>
	  <td align="left"><select name="payment_type"  id="payment_type" >
			  <option value="-1"> All </option>

			  <option value="0"> One-Off Payment </option>
			  <option value="1"> Subscription </option>



		  </select>

    <!--
    <a id="export_csv" onmouseover="MM_swapImage('exportcsv_btn','','/admin/images/buttons/cmsbutton-Export_CSV-over.gif',0)" onmouseout="MM_swapImgRestore()" href="orders.php?incomplete=yes"><img name="exportcsv_btn" src="/admin/images/buttons/cmsbutton-Export_CSV-off.gif" style="border:none" /> </a>
    
          <a onmouseover="MM_swapImage('Create_Pick_List_and_Update_Files_btn','','/admin/images/buttons/cmsbutton-Create_Pick_List_and_Update_Orders_to_Picking_in_Process-over.gif',0)" onmouseout="MM_swapImgRestore()" href="reports/pick_list_report.php?update_orders=1" target="_blank"> <img name="Create_Pick_List_and_Update_Files_btn" src="/admin/images/buttons/cmsbutton-Create_Pick_List_and_Update_Orders_to_Picking_in_Process-off.gif" style="border:none" /> </a>
      
      <a onmouseover="MM_swapImage('View_Pick_List_Without_Updating_Files_btn','','/admin/images/buttons/cmsbutton-View_Pick_List_Without_Updating_Files-over.gif',0)" onmouseout="MM_swapImgRestore()" href="reports/pick_list_report.php" target="_blank"> <img name="View_Pick_List_Without_Updating_Orders_btn" src="/admin/images/buttons/cmsbutton-View_Pick_List_Without_Updating_Orders-off.gif" style="border:none" /></a></td>
-->

 </td>
  </tr>
  <tr>
    <td class="adminfilter-right">Order Id</td>
    <td><input type="text" name="id" id="id" value="<? echo $_GET['id'] ?>" />
           <input type="button" name="clear_order_id" id="clear_order_id" value="Clear" onclick="clearfield('id')" />
      </td>
    <td class="adminfilter-right"><label for="start_date">Start Date</label></td>
    <td>
    <input type="text" name="start_date" id="start_date" value="<? echo $_GET['start_date'] ?>" />
           <input type="button"   value="Clear" onclick="clearfield('start_date')" /></td>
  </tr>
  <tr>
    <td class="adminfilter-right"><label for="email">Email</label> </td>
    <td>
      <input type="text" name="email" id="email" value="<? echo $_GET['email'] ?>" />
      <input type="button"   value="Clear" onclick="clearfield('email')" />
    </td>
    <td class="adminfilter-right"><label for="end_date">End Date</label></td>
    <td>
    <input type="text" name="end_date" id="end_date" value="<? echo $_GET['end_date'] ?>" />
       <input type="button"   value="Clear" onclick="clearfield('end_date')" />
    </td>
    
  </tr>
  <tr>
    <td class="adminfilter-right"><label for="firstname">Firstname</label></td>
    <td><input type="text" name="firstname" id="firstname"  value="<? echo $_GET['firstname'] ?>" />
      <input type="button"   value="Clear" onclick="clearfield('firstname')" /></td>
    <td class="adminfilter-right">Order By</td>
    <td><select name="order_by"  id="order_by" >
      <option value="0"> Default [status, date]  </option>
      <option value="2"> Date [Newest->Oldest] </option>
      <option value="1"> Date [Oldest->Newest] </option>
      <option value="3"> Customer Surname [A->Z] </option>
      <option value="4"> Customer Surname [Z->A] </option>
    
    </select></td>
  </tr>
  <tr>
    <td class="adminfilter-right">Surname</td>
    <td><input type="text" name="surname" id="surname" value="<? echo $_GET['surname'] ?>" />
    <input type="button"   value="Clear" onclick="clearfield('surname')" /></td>
    <td class="adminfilter-blank">&nbsp;</td>
    <td class="adminfilter-blank">
   <!-- <img name="Search_btn" src="http://www.local-conxa.com/admin/images/buttons/cmsbutton-Search-off.gif" style="border:none" /> -->
    <input type="submit" value="Filter Orders"  id="submit_filter_orders" /></td>
  </tr>
</table>

</form>