<?php
require('report_data.php');  
class PickListData extends ReportData
{
    function PickListData()
    {
        $this->sql =  'select p.name, p.stock_code, oi.*  '.
                'from shop_order_item oi '.
                'join shop_order o on o.id = oi.order_id  '.
                'join shop_product p on oi.product_id = p.id '.
                'where tender_type = 1 and status = 3';
        $headers = array('Name', 'Stock Code', 'Quantity', 'Price', 'Vat', 'Commission');
        $this->headers = implode("\t", $headers);
        $this->csv_fields = array('name', 'stock_code', 'quantity', 'net_price', 'vat', 'commission');
    }
}
?>
