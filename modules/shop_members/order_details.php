<?php if (isset($_POST['submit_basket_add'])) { ?>
    <script>
        showFadeInmessage(' Items Added to Basket ');
    </script>
    <?php
}
$output .= '<script type="text/javascript" src="/modules/members/js/order_details.js"></script>';
$order_id = $_GET['order_id'];
// will need to do details but for now just want to get items listed 
// will need to secure this page 
// added the $_SESSION["session_member_id"] to ensure we don't have a peek at someone elses orders
$sql = " SELECT so.id, time_made, total_price, tax, delivery, sos.`name` AS status FROM shop_order so
INNER JOIN shop_order_status sos ON sos.id = so.status
WHERE so.id = $order_id  AND so.trade_id =  " . $_SESSION["session_member_id"] . "  ORDER BY so.time_made DESC";


$result = mysql_query($sql);
while ($row = mysql_fetch_array($result)) {
    $output .= '<h3> Order Id: ' . $row['id'] . '</h3>';
    $output .= '<p> <b>Date:</b> ' . $row['time_made'] . '';
    $output .= '<br> <b>Status:</b> ' . $row['status'] . '</p>';
    $total_tax = sprintf("%01.2f", $row['tax']);
    $total_delivery = sprintf("%01.2f", $row['delivery']);
    $total_total = sprintf("%01.2f", $row['total_price']);
}
$sql = "SELECT soi.id, soi.product_id, soi.quantity,  soi.price,  soi.comm_tax, soi.colour_id, 
soi.discount_text,  so.tax AS order_tax, soi.`description`
FROM shop_order_item soi 
INNER JOIN shop_order so ON so.id = soi.`order_id`
WHERE soi.order_id = $order_id";
$result = mysql_query($sql);
$colourArrForJS = '';
$output .= '<table class="orderlist">
      <tr class="orderlisttitle">
        <td align="center">Product ID</td>
        <td align="center">Item</td>
        <td align="center">Quantity</td>
        <td align="center">Item cost</td>
        <td align="center">Line Total</td>
      
      </tr>
    ';
while ($row = mysql_fetch_array($result)) {
    $output .= '<tr><td align="center">' . $row['product_id'] . '</td>';
    $discount_text = "";
    if ($row['discount_text'] != '') {
        $discount_text = "<br> " . $row['discount_text'];
    }
    $output .= '<td align="left">' . $row['description'] . $discount_text . '</td>';
    $output .= '<td align="center">' . $row['quantity'] . '</td>';
    $output .= '<td align="center"> &pound;' . sprintf("%01.2f", $row['price']) . '</td>';
    // $output .= '<td align="center"> &pound;' . sprintf("%01.2f", $row['comm_tax']) . '</td>';
    $output .= '<td align="center"> &pound;' . sprintf("%01.2f", ($row['quantity'] * $row['price'])) . '</td>';



    $output .= '</tr> ';
    $colourArrForJS .= $row['colour_id'] . ',';
    $order_tax = sprintf("%01.2f", $row['order_tax']);
}
$output .= '<tr>
	<td  > </td>
	<td align="left" >Delivery   </td>
	<td > </td>
	<td   align="center"></td>
	<td  align="center" >&pound;' . $total_delivery . '</td>
 
	</tr>';
	
	 if( SHOP_SHOW_VAT == '1'){
	
	 $output .= '<tr>
	<td  > </td>
	<td align="left" >Tax  </td>
	<td > </td>
	<td   align="center"></td>
	<td  align="center" >&pound;' . $order_tax . '</td>
 
	</tr>';
	
	 }
$output .= '<tr class="orderlisttotal">
	<td align="left" colspan="4" >Total (inc Tax)</td>
	<td align="center" >&pound;' . sprintf("%01.2f", $total_total ) . '</td>
 
	</tr>';
$output .= '</table>';
$output .= '<br><a href="/' . $name_parts[0] . '/orders-list" >Back</a>';
echo $output;
?><form id="basket_all" action="" method="post">
    <input type="hidden" value="submit_basket_add" name="submit_basket_add">
    <input id="product_id" type="hidden" value="<?php echo $order_id; ?>" name="product_id">
</form>
