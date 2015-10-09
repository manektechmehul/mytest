<?php
$id = $_SESSION["session_member_id"];
$sql = " SELECT so.id, time_made, total_price, tax, delivery, sos.`name` AS status FROM shop_order so
INNER JOIN shop_order_status sos ON sos.id = so.status  
 WHERE so.trade_id = $id and so.status>0 ORDER BY so.time_made DESC";

$no_orders = true;

$result = mysql_query($sql);
$output .= '<p>Find your orders below.</p><p>To view the details of an order, click on the Order ID column to the left.</p>';



$output .= '<table class="orderlist" border="0" cellspacing="0" cellpadding="0">
      <tr class="orderlisttitle">
        <td align="center">Order ID</td>
        <td align="center">Date</td>
        <td align="center">Status</td>';
		
		
       if( SHOP_SHOW_VAT == '1'){
		$output .= '<td bgcolor="#CCCCCC" align="center" >Tax</td><td align="center">Items Total</td>
        <td align="center">Delivery</td>';
	   }
    
	
	    $output .= '<td align="center">Grand Total</td></tr>';

while ($row = mysql_fetch_array($result)) {
    $no_orders = false;
    $output .= '<tr><td align="center"><a href="/' . $name_parts[0] . '/order-details?order_id=' . $row['id'] . '">' . $row['id'] . '</a></td>';
    $output .= '<td align="center" >' . $row['time_made'] . '</td>';
    $output .= '<td align="center" >' . $row['status'] . '</td>';
	 if( SHOP_SHOW_VAT == '1'){
    $output .= '<td align="center">&pound;' . sprintf("%01.2f", $row['tax']) . '</td>';
    $output .= '<td align="center" >&pound;' . sprintf("%01.2f", $row['total_price']) . '</td>';
    $output .= '<td align="center">&pound;' . sprintf("%01.2f", $row['delivery']) . '</td>';
    $output .= '<td align="center" >&pound;' . sprintf("%01.2f", ( $row['total_price'] + $row['tax'])) . '</td>';
	}else{
		 $output .= '<td align="center" >&pound;' . sprintf("%01.2f", $row['total_price'] ) . '</td>';
	}
    $output .= '</tr> ';
}
$output .= '</table></div>';
if($no_orders){
    echo "<p>You haven't made any orders yet.</p>";
}else{
    echo $output;
}
?>